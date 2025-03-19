<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class InvoiceItemController extends Controller
{
    public function index()
    {
        return response()->json(InvoiceItem::all());
    }

    public function store(Request $request, $invoice_id)
    {
        // Validate request
        $data = $request->validate([
            'item_code' => 'required|string',
            'item_description_original' => 'required|string',
            'item_description' => 'required|string',
            'quantity' => 'required|integer',
            'base_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'currency' => 'required|string',
            'best_customs_code_matches' => 'required|array', // Change to array if coming as array
        ]);
    
        // Ensure the invoice exists
        $invoice = Invoice::findOrFail($invoice_id);
    
        // Add invoice_id to the data
        $data['invoice_id'] = $invoice_id;
    
        // Create item and associate it with the invoice
        $invoiceItem = $invoice->items()->create($data);
    
        return response()->json($invoiceItem, 201);
    }
    
    public function getInvoiceItemsSingleInvoice($invoiceId)
{
    try {
        // Retrieve all items that belong to the given invoice
        $invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();

        if ($invoiceItems->isEmpty()) {
            throw new ModelNotFoundException();
        }

        return response()->json($invoiceItems);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'No invoice items found for the specified invoice'], 404);
    }
}

public function update(Request $request, $invoiceItemId)
{
    try {
        // Retrieve the specific invoice item by ID
        $invoiceItem = InvoiceItem::findOrFail($invoiceItemId);

        // Validate the request data
        $data = $request->validate([
            'item_code' => 'required|string',
            'item_description_original' => 'required|string',
            'item_description' => 'required|string',
            'quantity' => 'required|integer',
            'base_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'currency' => 'required|string',
            'best_customs_code_matches' => 'required|array',
        ]);

        // Update the invoice item
        $invoiceItem->update($data);

        return response()->json([
            'message' => 'Invoice item updated successfully',
            'data' => $invoiceItem
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Invoice item not found'], 404);
    } catch (Exception $e) {
        return response()->json(['error' => 'Failed to update invoice item: ' . $e->getMessage()], 500);
    }
}


    public function destroy(InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete();
        return response()->json(['message' => 'Invoice Item deleted']);
    }
}
