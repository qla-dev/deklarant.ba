<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Jobs\StoreInvoiceItemsJob;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $invoices = Invoice::with('items')->get();
            return response()->json($invoices);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoices. Please try again later.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::with('items')->findOrFail($id);
            return response()->json($invoice);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice not found with the given ID.'], 404);
        }
    }

    public function getInvoicesBySupplier($supplierId)
    {
        try {
            $invoices = Invoice::where('supplier_id', $supplierId)
                ->with(['items'])
                ->get();
            
            if ($invoices->isEmpty()) {
                return response()->json(['error' => 'No invoices found for the specified supplier.'], 404);
            }

            return response()->json($invoices);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoices. Please check the supplier ID and try again.'], 500);
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
            return response()->json(['error' => 'No invoices found for the specified user.'], 404);
        }

        return response()->json($invoices);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500); // for debugging
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
                'message' => 'Invoice created successfully.',
                'data' => $invoice
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Supplier not found. Please check the supplier ID and try again.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create invoice. ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->update($request->all());

            return response()->json([
                'message' => 'Invoice updated successfully.',
                'data' => $invoice
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update invoice. Please check the data and try again.'], 500);
        }
    }

    public function destroy($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->delete();

            return response()->json(['message' => 'Invoice deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete invoice. Please try again later.'], 500);
        }
    }

    public function scan($invoiceId)
{
    $start = microtime(true); // Start time

    try {
        $invoice = Invoice::findOrFail($invoiceId);

        if ($invoice->scanned == 1) {
            return response()->json([
                'message' => 'This invoice has already been scanned.'
            ], 409); // 409 = Conflict
        }

        $invoice->scanned = 1;

        $duration = microtime(true) - $start; // Duration in seconds (float)
        $invoice->scan_time = round($duration, 3); // Rounded to 3 decimal places
        $invoice->save();

        return response()->json([
            'message' => 'Invoice scanned successfully.',
            'data' => $invoice
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Invoice not found with the given ID.'], 404);
    } catch (Exception $e) {
        return response()->json(['error' => 'Failed to scan invoice. Please try again later.'], 500);
    }
}

    public function getInvoiceInfoById($id)
{
    $invoice = Invoice::find($id);

    if (!$invoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
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
            'items.*.version' => 'required|integer',
            'items.*.best_customs_code_matches' => 'required|array',
        ]);

        $supplier = Supplier::findOrFail($supplierId);
        $user = User::findOrFail($userId);

        // Save the invoice first
        $invoice = Invoice::create([
            'user_id' => $userId,
            'supplier_id' => $supplier->id,
            'file_name' => $data['file_name'] ?? null,
            'total_price' => $data['total_price'],
            'date_of_issue' => $data['date_of_issue'],
            'country_of_origin' => $data['country_of_origin'],
        ]);

        // Dispatch job to store invoice items
        StoreInvoiceItemsJob::dispatch($invoice->id, $data['items']);

        return response()->json([
            'message' => 'Invoice created successfully. Invoice items are being processed.',
            'data' => [
                'invoice_id' => $invoice->id,
                'file_name' => $invoice->file_name,
                'total_price' => $invoice->total_price,
                'date_of_issue' => $invoice->date_of_issue,
                'country_of_origin' => $invoice->country_of_origin,
                'scanned' => $invoice->scanned, // Include scanned field
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
        return response()->json(['error' => 'Supplier or user not found. Please check IDs and try again.'], 404);
    } catch (Exception $e) {
        return response()->json(['error' => 'Failed to create invoice. ' . $e->getMessage()], 500);
    }
}





}
