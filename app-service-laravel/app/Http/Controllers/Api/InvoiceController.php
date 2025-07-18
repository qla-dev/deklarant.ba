<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Supplier;
use App\Models\Importer;
use App\Models\User;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Jobs\StoreInvoiceItemsJob;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // At the top of your controller
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    private function handleInternalError(Exception $exception, string $errorMessage)
    {
        Log::error($errorMessage . ': ' . $exception->getMessage() . '. Stacktrace: ' . $exception->getTraceAsString());
        return response()->json(['error' => $errorMessage], 500);
    }

    public function index()
    {
        try {
            $invoices = Invoice::with('items')->get();
            return response()->json($invoices);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno preuzimanje deklaracija. Pokušajte ponovo kasnije');
        }
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::with('items')->findOrFail($id);
            $this->fillWithAiDataIfNecessary($invoice);
            return response()->json($invoice);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija s unesenim ID-om nije pronađena'], 404);
        }
    }

    public function getInvoicesBySupplier($supplierId)
    {
        try {
            /** @var \App\Models\Invoice $invoice */
            $invoices = Invoice::where('supplier_id', $supplierId)
                ->with(['items'])
                ->get();

            if ($invoices->isEmpty()) {
                return response()->json(['error' => 'Nisu pronađene deklaracije za navedenog dobavljača'], 404);
            }

            return response()->json($invoices);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno preuzimanje deklaracija. Provjerite ID dobavljača i pokušajte ponovo');
        }
    }

    public function getInvoicesByUser($userId)
    {
        try {
            $invoices = Invoice::where('user_id', $userId)
                ->with([
                    'importer:id,name,owner,avatar' // Make sure this line is correct
                ])
                ->get();

            if ($invoices->isEmpty()) {
                return response()->json(['error' => 'Nisu pronađene deklaracije za navedenog korisnika'], 404);
            }

            foreach ($invoices as $invoice) {
                $invoice->updateInternalStatusIfNecessary();
            }

            return response()->json($invoices);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno preuzimanje deklaracija. Pokušajte ponovo kasnije');
        }
    }

    public function store(Request $request, $userId, $supplierId)
    {
        try {
            $data = $request->validate([
                'file_name' => 'string',
                'total_price' => 'required|numeric',
                'date_of_issue' => 'required|date',
                'country_of_origin' => 'required|string',
            ]);

            $supplier = Supplier::findOrFail($supplierId);

            $invoice = Invoice::create([
                'user_id' => $userId,
                'supplier_id' => $supplier->id,
                'file_name' => $data['file_name'],
                'total_price' => $data['total_price'],
                'date_of_issue' => $data['date_of_issue'],
                'country_of_origin' => $data['country_of_origin'],
            ]);

            return response()->json([
                'message' => 'Deklaracija uspješno kreirana',
                'data' => $invoice
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Dobavljač nije pronađen. Provjerite ID dobavljača i pokušajte ponovo'], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => "Neuspješno kreiranje deklaracije",
                // ukloniti uglaste zagrade ako treba ispisati sve errore a ne samo prvi
                'message' => $e->errors()[array_key_first($e->errors())][0]
            ], 422);
        }
        catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno kreiranje deklaracije. Pokušajte ponovo kasnije');
        }

    }

    public function update(Request $request, $invoiceId)
    {
        DB::beginTransaction();

        try {
            $invoice = Invoice::with('items')->findOrFail($invoiceId);
            $data = $request->all();

            // Localized decimal parser
            $normalizeDecimal = function ($value) {
                return is_numeric($value) ? floatval($value) : floatval(str_replace(',', '.', preg_replace('/\./', '', $value)));
            };

            // Update invoice metadata
            $invoice->update([
                'file_name' => $data['file_name'] ?? $invoice->file_name,
                'total_price' => isset($data['total_price']) ? $normalizeDecimal($data['total_price']) : $invoice->total_price,
                'date_of_issue' => $data['date_of_issue'] ?? $invoice->date_of_issue,
                'country_of_origin' => $data['country_of_origin'] ?? $invoice->country_of_origin,
                'importer_id' => $data['importer_id'] ?? $invoice->importer_id,
                'supplier_id' => $data['supplier_id'] ?? $invoice->supplier_id,
                'invoice_number' => $data['invoice_number'] ?? $invoice->invoice_number,
                'incoterm' => $data['incoterm'] ?? $invoice->incoterm,
                'incoterm_destination' => $data['incoterm_destination'] ?? $invoice->incoterm_destination,
                'total_weight_net'   => isset($data['total_weight_net'])   ? floatval(str_replace(',', '.', $data['total_weight_net']))   : $invoice->total_weight_gross,
                'total_weight_gross'   => isset($data['total_weight_gross'])   ? floatval(str_replace(',', '.', $data['total_weight_gross']))   : $invoice->total_weight_gross,
                'total_num_packages' => $data['total_num_packages'] ?? $invoice->total_num_packages,
                'internal_status' => array_key_exists('internal_status', $data) ? $data['internal_status'] : 2,
            ]);

            // Process items
            $submittedItems = $data['items'] ?? [];
            $submittedIds = [];

            foreach ($submittedItems as $item) {
                $basePrice = isset($item['base_price']) ? $normalizeDecimal($item['base_price']) : null;
                $totalPrice = isset($item['total_price']) ? $normalizeDecimal($item['total_price']) : null;

                $itemData = [
                    'item_code' => $item['item_code'],
                    'item_description_original' => $item['item_description_original'],
                    'item_description' => $item['item_description'],
                    'item_description_translated' => $item['item_description_translated'] ?? null,
                    'quantity' => $item['quantity'],
                    'slot_number' => $item['slot_number'],
                    'base_price' => $basePrice,
                    'total_price' => $totalPrice,
                    'currency' => $item['currency'],
                    'version' => $item['version'],
                    'best_customs_code_matches' => $item['best_customs_code_matches'] ?? [],
                    'country_of_origin' => $item['country_of_origin'] ?? null,
                    'quantity_type' => $item['quantity_type'] ?? null,
                    'num_packages' => $item['num_packages'] ?? null,
                    'weight_gross' => isset($item['weight_gross']) ? floatval(str_replace(',', '.', $item['weight_gross'])): null,
                    'weight_net'   => isset($item['weight_net']) ? floatval(str_replace(',', '.', $item['weight_net'])) : null,
                    'tariff_privilege' => $item['tariff_privilege'] !== '0' ? $item['tariff_privilege'] : null,
                    'num_packages_locked' => $item['num_packages_locked'] ?? false,
                    'weight_gross_locked'   => $item['weight_gross_locked'] ?? false,
                    'weight_net_locked'   => $item['weight_net_locked'] ?? false,
                ];

                if (!empty($item['item_id'])) {
                    $existingItem = $invoice->items()->find($item['item_id']);
                    if ($existingItem) {
                        $existingItem->update($itemData);
                        $submittedIds[] = $existingItem->id;
                    }
                } else {
                    $newItem = $invoice->items()->create([
                        ...$itemData,
                        'tariff_privilege' => $item['tariff_privilege'] === 'DA' ? 1 : 0,
                    ]);
                    $submittedIds[] = $newItem->id;
                }
            }

            // Delete removed items
            $invoice->items()->whereNotIn('id', $submittedIds)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Deklaracija i stavke uspješno ažurirane',
                'data' => $invoice->fresh('items')
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning("Deklaracija nije pronađena. ID: $invoiceId", [
                'exception' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Deklaracija s unesenim ID-om nije pronađena'], 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Greška pri ažuriranju deklaracije. ID: $invoiceId", [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $translatedMessage = 'Došlo je do greške prilikom obrade deklaracije.';
            $message = $e->getMessage();

            if (str_contains($message, 'Integrity constraint violation')) {
                if (str_contains($message, 'item_description_original')) {
                    $translatedMessage = 'Naziv i opis svih proizvoda unutar deklaracije su obavezni';
                } elseif (str_contains($message, 'country_of_origin')) {
                    $translatedMessage = 'Zemlja porijekla je obavezna.';
                } elseif (str_contains($message, 'item_code')) {
                    $translatedMessage = 'Tarifni brojevi svih proizvoda unutar deklaracije su obavezni';
                } else {
                    $translatedMessage = 'Neki od obaveznih podataka nedostaje ili nije ispravan.';
                }
            }

            return response()->json([
                'error' => 'Neuspješno ažuriranje deklaracije',
                'backend_error' => $message,
                'poruka' => $translatedMessage
            ], 500);
        }
    }

    public function destroy($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->delete();

            return response()->json(['message' => 'Deklaracija uspješno izbrisana']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija s unesenim ID-om nije pronađena'], 404);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno brisanje deklaracije. Pokušajte ponovo kasnije');
        }
    }

    public function scan($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);

            if (empty($invoice->file_name)) {
                return response()->json([
                    'error' => 'Deklaracija nema priloženu datoteku za skeniranje'
                ], 400);
            }

            $filePath = public_path('uploads/original_documents/' . $invoice->file_name);

            if (!file_exists($filePath)) {
                \Log::error('File not found on disk', [
                    'invoice_id' => $invoiceId,
                    'file_name' => $invoice->file_name,
                    'full_path' => $filePath
                ]);
                return response()->json([
                    'error' => 'Datoteka nije pronađena: ' . $invoice->file_name
                ], 404);
            }

            $user = auth()->user();
            $canUsePaidModels = $user->can_use_paid_models;
            $response = app(AiService::class)->uploadDocument($filePath, $invoice->file_name, $canUsePaidModels);
            $taskId = $response['task_id'] ?? null;

            if (!$taskId) {
                throw new Exception('AI server nije vratio ID naloga');
            }

            $invoice->task_id = $taskId;
            $invoice->internal_status = 0;
            $invoice->save();

            return response()->json([
                'message' => 'Deklaracija je poslana na AI obradu',
                'task_id' => $taskId,
                'data' => $invoice
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija s unesenim ID-om nije pronađena'], 404);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno skeniranje deklaracije. Pokušajte ponovo kasnije');
        }
    }

    public function getInvoiceInfoById($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['error' => 'Deklaracija nije pronađena'], 404);
        }

        $supplier = Supplier::find($invoice->supplier_id);

        return response()->json([
            'file_name' => $invoice->file_name,
            'total_price' => $invoice->total_price,
            'supplier_id' => $invoice->supplier_id,
            'supplier_name' => $supplier->name ?? null,
            'supplier_avatar' => $supplier->avatar ?? null,
            'owner' => $supplier->owner ?? null,
            'user_id' => $invoice->user_id,
            'overall_status' => $invoice->overallStatus(), // ✅ Nova linija
        ]);
    }

    public function getScanStatus($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            // Verify invoice belongs to current user
            if ($invoice->user_id !== auth()->id()) {
                return response()->json(['error' => 'Neovlašten pristup deklaraciji'], 403);
            }

            $status = $invoice->getStatusFromAI();

            if (!$status) {
                return response()->json(['error' => 'Nalog za skeniranje nije pronađen'], 404);
            }

            return response()->json([
                'status' => $status,
                'invoice_id' => $invoice->id,
                'task_id' => $invoice->task_id
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija nije pronađena'], 404);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno dohvaćanje statusa skeniranja. Pokušajte ponovo kasnije');
        }
    }

    private function tryGetAiResult($invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);

        // Verify invoice belongs to current user
        if ($invoice->user_id !== auth()->id()) {
            return response()->json(['error' => 'Neovlašten pristup deklaraciji'], 403);
        }

        // Check if invoice has task_id
        if (!$invoice->task_id) {
            return response()->json(['error' => 'Nijedan nalog skeniranja nije povezan s ovom deklaracijom'], 404);
        }

        // Get task result from AI service
        $result = $invoice->getTaskResultFromAI();

        if (!$result) {
            return response()->json(['error' => 'Rezultat skeniranja nije pronađen'], 404);
        }

        return $result;
    }

    public function getScanResult($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $result = $this->tryGetAiResult($id);
            // Check if it's a Laravel HTTP Response
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                // Handle the error response
                return $result;
            }
            return response()->json([
                'result' => $result,
                'invoice_id' => $invoice->id,
                'task_id' => $invoice->task_id
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija nije pronađena'], 404);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno dohvaćanje statusa skeniranja. Pokušajte ponovo kasnije');
        }
    }

    public function getScanParties($id)
    {
        try {
            $result = $this->tryGetAiResult($id);
            // Check if it's a Laravel HTTP Response
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                // Handle the error response
                return $result;
            }
            // TODO: Change to importer
            $importer = Importer::where('name', $result['importer']['name'])->first();
            $supplier = Supplier::where('name', $result['supplier']['name'])->first();

            $importer_id = $importer ? $importer->id : null;
            $supplier_id = $supplier ? $supplier->id : null;
            return response()->json([
                'supplier' => $result['supplier'],
                'importer' => $result['importer'],
                'supplier_id' => $supplier_id,
                'importer_id' => $importer_id,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija nije pronađena'], 404);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno dohvaćanje statusa skeniranja. Pokušajte ponovo kasnije');
        }
    }

    # returns string or null
    private function getTariffPrivilageFromCountry(string $country): ?string
    {
        switch ($country) {
            # Turkey
            case 'TR':
                return 'TRP';
            # CEFTA/AP
            case 'AL':
            case 'XK':
            case 'MD':
            case 'ME':
            case 'MK':
            case 'RS':
                return 'CEFTA/AP';
            # Iran
            case 'IR':
                return 'IRP';
            # EU
            case 'AT':
            case 'BE':
            case 'BG':
            case 'HR':
            case 'CY':
            case 'CZ':
            case 'DK':
            case 'EE':
            case 'FI':
            case 'FR':
            case 'DE':
            case 'GR':
            case 'HU':
            case 'IE':
            case 'IT':
            case 'LV':
            case 'LT':
            case 'LU':
            case 'MT':
            case 'NL':
            case 'PL':
            case 'PT':
            case 'RO':
            case 'SK':
            case 'SI':
            case 'ES':
            case 'SE':
                return 'EUP';
            default:
                return null;
        }
    }

    private function fillWithAiDataIfNecessary(Invoice $invoice)
    {
        // Skip if invoice has no task_id or already has items
        if (!$invoice->task_id || $invoice->items->isNotEmpty()) {
            return;
        }

        try {
            $invoice->updateInternalStatusIfNecessary();
            $result = $invoice->getTaskResultFromAI();

            if (empty($result['items'])) {
                return;
            }

            // Create invoice items from AI data
            $items = array_map(function ($item) {
                // Check if item has hs_code with length of 10 after removing whitespace
                $hs_code = str_replace(' ', '', $item['hs_code'] ?? '');
                if ($hs_code && strlen($hs_code) >= 10) {
                    $item_code = substr($hs_code, 0, 4) . ' ' . substr($hs_code, 4, 2) . ' ' . substr($hs_code, 6, 2) . ' ' . substr($hs_code, 8);
                } else {
                    // Find the best entry from detected codes
                    $best_entry = null;
                    foreach ($item['detected_codes'] as $entry) {
                        if (is_null($best_entry) || $entry['closeness'] < $best_entry['closeness']) {
                            $best_entry = $entry;
                        }
                    }
                    $item_code = $best_entry ? $best_entry['entry']['Tarifna oznaka'] : null;
                }

                return [
                    'version' => 1,
                    'item_code' => $item_code,
                    'item_description_original' => $item['original_name'],
                    'item_description' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'base_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'currency' => $item['currency'],
                    'best_customs_code_matches' => $item['detected_codes'] ?? [],
                    'country_of_origin' => $item['country_of_origin'],
                    'quantity_type' => $item['quantity_type'],
                    'num_packages' => $item['num_packages'] ?? null,
                    'weight_gross' => $item['weight_gross'] ?? null,
                    'weight_net' => $item['weight_net'] ?? null,
                    'num_packages_locked' => !empty($item['num_packages']),
                    'weight_gross_locked' => !empty($item['weight_gross']),
                    'weight_net_locked' => !empty($item['weight_net']),
                    'item_description_translated' => $item['item_description_translated'],
                    'tariff_privilege' => $this->getTariffPrivilageFromCountry($item['country_of_origin']),
                ];
            }, $result['items']);

            $invoice->update([
                'incoterm' => $result['invoice_info']['incoterm'],
                'invoice_number' => $result['invoice_info']['invoice_number'],
                'internal_status' => 1,
                'total_weight_net' => $result['invoice_info']['total_weight_net'] ?? null,
                'total_weight_gross' => $result['invoice_info']['total_weight_gross'] ?? null,
                'total_num_packages' => $result['invoice_info']['total_num_packages'] ?? null,
                'incoterm_destination' => $result['invoice_info']['incoterm_destination'] ?? null
            ]);

            // Save items
            $invoice->items()->createMany($items);

            // Reload invoice with fresh items
            $invoice->load('items');

        } catch (Exception $e) {
            // Log error but don't fail the request
            \Log::error($e);
            \Log::error("Neuspješno popunjavanje deklaracije AI podacima. Pokušajte ponovo kasnije" );
        }
    }

    public function storeInvoicesWithItems(Request $request, $userId, $supplierId)
    {
        try {
            $data = $request->validate([
                'file_name' => 'nullable|string',
                'total_price' => 'required|numeric',
                'date_of_issue' => 'required|date',
                'country_of_origin' => 'required|string',
                'items' => 'required|array',
                'items.*.item_code' => 'required|string',
                'items.*.item_description_original' => 'required|string',
                'items.*.item_description' => 'required|string',
                'items.*.quantity' => 'required|integer',
                'items.*.base_price' => 'required|numeric',
                'items.*.total_price' => 'required|numeric',
                'items.*.slot_number' => 'numeric',
                'items.*.currency' => 'required|string',
                'importer_id' => 'required|integer|exists:importers,id',
                'items.*.version' => 'required|integer',
                'items.*.best_customs_code_matches' => 'required|array',
            ]);

            $supplier = Supplier::findOrFail($supplierId);
            $importer = Importer::findOrFail($data['importer_id']);
            $user = User::findOrFail($userId);

            // Save the invoice first
            $invoice = Invoice::create([
                'user_id' => $userId,
                'supplier_id' => $supplier->id,
                'importer_id' => $importer->id,
                'file_name' => $data['file_name'] ?? null,
                'total_price' => $data['total_price'],
                'date_of_issue' => $data['date_of_issue'],
                'country_of_origin' => $data['country_of_origin'],
            ]);

            // Dispatch job to store invoice items
            StoreInvoiceItemsJob::dispatch($invoice->id, $data['items']);

            return response()->json([
                'message' => 'Deklaracija je uspješno kreirana. Stavke deklaracije se obrađuju',
                'data' => [
                    'invoice_id' => $invoice->id,
                    'file_name' => $invoice->file_name,
                    'total_price' => $invoice->total_price,
                    'date_of_issue' => $invoice->date_of_issue,
                    'country_of_origin' => $invoice->country_of_origin,
                    'scanned' => $invoice->task_id != null ? 1 : 0, // Include scanned field
                    'supplier' => [
                        'name' => $supplier->name,
                        'contact_phone' => $supplier->contact_phone,
                        'tax_id' => $supplier->tax_id,
                    ],
                    'user' => [
                        'city' => $user->city,
                        'zip_code' => $user->zip_code,
                        'email' => $user->email,
                        'website' => $user->website,
                        'phone_number' => $user->phone_number,
                    ],
                ],
            ], 201);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Dobavljač ili korisnik nije pronađen. Provjerite ID-ove i pokušajte ponovo'], 404);
        } catch (Exception $e) {
            return $this->handleInternalError($e, 'Neuspješno preuzimanje AI podataka. Pokušajte ponovo kasnije');
        }
    }
}
