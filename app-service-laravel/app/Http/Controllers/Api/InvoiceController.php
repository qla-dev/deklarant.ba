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
    public function index()
    {
        try {
            $invoices = Invoice::with('items')->get();
            return response()->json($invoices);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje deklaracija. Pokušajte ponovo kasnije'], 500);
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
            return response()->json(['error' => 'Neuspješno preuzimanje deklaracija. Provjerite ID dobavljača i pokušajte ponovo'], 500);
        }
    }


    public function getInvoicesByUser($userId)
    {
        try {
            $invoices = Invoice::where('user_id', $userId)
                ->with([
                    'items',
                    'supplier:id,name,owner,avatar' // Make sure this line is correct
                ])
                ->get();

            if ($invoices->isEmpty()) {
                return response()->json(['error' => 'Nisu pronađene deklaracije za navedenog korisnika'], 404);
            }

            return response()->json($invoices);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje deklaracija. Pokušajte ponovo kasnije'], 500);
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
            return response()->json(['error' => 'Neuspješno kreiranje deklaracije. Pokušajte ponovo kasnije'], 500);
        }

    }

public function update(Request $request, $invoiceId)
{
    try {
        $invoice = Invoice::findOrFail($invoiceId);
        $data = $request->all();
        if (isset($data['importer_id'])) {
            $invoice->importer_id = $data['importer_id'];
        }
        $invoice->update($data);

        return response()->json([
            'message' => 'Deklaracija uspješno ažurirana',
            'data' => $invoice
        ]);
    } catch (ModelNotFoundException $e) {
        Log::warning("Deklaracija nije pronađena. ID: $invoiceId", [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json(['error' => 'Deklaracija s unesenim ID-om nije pronađena'], 404);
    } catch (Exception $e) {
        Log::error("Greška pri ažuriranju deklaracije. ID: $invoiceId", [
            'request_data' => $request->all(),
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json(['error' => 'Neuspješno ažuriranje deklaracije. Provjerite podatke i pokušajte ponovo'], 500);
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
            return response()->json(['error' => 'Neuspješno brisanje deklaracije. Pokušajte ponovo kasnije'], 500);
        }

    }

    public function scan($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);

            if ($invoice->task_id !== null) {
                return response()->json([
                    'message' => 'Deklaracija je već skenirana'
                ], 409); // 409 = Conflict
            }

            if (empty($invoice->file_name)) {
                return response()->json([
                    'error' => 'Deklaracija nema priloženu datoteku za skeniranje'
                ], 400);
            }

            $filePath = Storage::disk('public')->path('uploads/' . $invoice->file_name);
            if (!file_exists($filePath)) {
                return response()->json([
                    'error' => 'Datoteka nije pronađena: ' . $invoice->file_name
                ], 404);
            }

            $aiService = app(AiService::class);

            $response = $aiService->uploadDocument($filePath, $invoice->file_name);
            $taskId = $response['task_id'] ?? null;

            if (!$taskId) {
                throw new Exception('AI server nije vratio ID naloga');
            }

            $invoice->task_id = $taskId;
            $invoice->save();

            return response()->json([
                'message' => 'Deklaracija je poslana na AI obradu',
                'task_id' => $taskId,
                'data' => $invoice
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija s unesenim ID-om nije pronađena'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno skeniranje deklaracije. Pokušajte ponovo kasnije'], 500);
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
            'owner' => $supplier->owner ?? null, // Make sure this column exists in the DB
            'user_id' => $invoice->user_id,
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

            // Check if invoice has task_id
            if (!$invoice->task_id) {
                return response()->json(['error' => 'Nijedan nalog skeniranja nije povezan s ovom deklaracijom'], 404);
            }

            
            $status = app(AiService::class)->getTaskStatus($invoice->task_id);

            if (!$status) {
                return response()->json(['error' => 'Nalog za skeniranje nije pronađen'], 404);
            }
            
            if ($invoice->user_id !== auth()->id()) {
                return response()->json(['error' => 'Niste ovlašteni za pristup ovoj deklaraciji'], 403);
            }

            // Provjera da li deklaracija ima task_id
            if (!$invoice->task_id) {
                return response()->json(['error' => 'Nijedan nalog skeniranja nije povezan s ovom deklaracijom'], 404);
            }

            // Get task status from AI service
            $status = app(AiService::class)->getTaskStatus($invoice->task_id);

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
            return response()->json(['error' => 'Neuspješno dohvaćanje statusa skeniranja. Pokušajte ponovo kasnije'], 500);
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
        $result = app(AiService::class)->getTaskResult($invoice->task_id);

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
            return response()->json(['error' => 'Neuspješno dohvaćanje statusa skeniranja. Pokušajte ponovo kasnije'], 500);
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
            return response()->json(['error' => 'Neuspješno dohvaćanje statusa skeniranja. Pokušajte ponovo kasnije'], 500);
        }
    }

    private function fillWithAiDataIfNecessary(Invoice $invoice)
    {
        // Skip if invoice has no task_id or already has items
        if (!$invoice->task_id || $invoice->items->isNotEmpty()) {
            return;
        }

        try {
            $aiService = app(AiService::class);
            $result = $aiService->getTaskResult($invoice->task_id);
            
            if (empty($result['items'])) {
                return;
            }

            // Create invoice items from AI data
            $items = array_map(function ($item) {
                $quantity = $item['quantity'] ?? 0;
                $base_price = $item['unit_price'] ?? 0;

                $best_entry = null;
                foreach ($item['detected_codes'] as $entry) {
                    if (is_null($best_entry) || $entry['closeness'] < $best_entry['closeness']) {
                        $best_entry = $entry;
                    }
                }
                $item_code = $best_entry ? $best_entry['entry']['Tarifna oznaka'] : null;

                return [
                    'version' => 1,
                    'item_code' => $item_code,
                    'item_description_original' => $item['original_name'],
                    'item_description' => $item['item_name'],
                    'quantity' => $quantity,
                    'base_price' => $base_price,
                    'total_price' => $base_price * $quantity,
                    'currency' => $item['currency'],
                    'best_customs_code_matches' => $item['detected_codes'] ?? [],
                    'country_of_origin' => $item['country_of_origin'],
                    'quantity_type' => $item['quantity_type'],
                    'num_packages' => $item['num_packages'],
                ];
            }, $result['items']);

            $invoice->update([
                'incoterm' => $result['invoice_info']['incoterm'],
                'invoice_number' => $result['invoice_info']['invoice_number']
            ]);

            // Save items
            $invoice->items()->createMany($items);

            // Reload invoice with fresh items
            $invoice->load('items');
            
        } catch (Exception $e) {
            // Log error but don't fail the request
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
            return response()->json(['error' => 'Neuspješno kreiranje deklaracije. Pokušajte ponovo kasnije'], 500);
        }

    }





}
