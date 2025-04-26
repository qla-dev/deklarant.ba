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
        try {
            $invoiceItems = InvoiceItem::all();
            return response()->json($invoiceItems);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoice items. Please try again later.'], 500);
        }
    }

    public function store(Request $request, $invoice_id)
    {
        try {
            $data = $request->validate([
                'item_code' => 'required|string',
                'version' => 'required|integer',
                'item_description_original' => 'required|string',
                'item_description' => 'required|string',
                'quantity' => 'required|integer',
                'base_price' => 'required|numeric',
                'total_price' => 'required|numeric',
                'currency' => 'required|string',
                'best_customs_code_matches' => 'required|array',
            ]);
    
            $invoice = Invoice::findOrFail($invoice_id);
    
            $data['invoice_id'] = $invoice_id;
    
            $invoiceItem = $invoice->items()->create($data);
    
            return response()->json([
                'message' => 'Invoice item created successfully.',
                'data' => $invoiceItem
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice not found. Please check the invoice ID and try again.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create invoice item: ' . $e->getMessage()], 500);
        }
    }
    
    public function getInvoiceItemsSingleInvoice($invoiceId)
    {
        try {
            $invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();
    
            if ($invoiceItems->isEmpty()) {
                return response()->json(['error' => 'No invoice items found for the specified invoice.'], 404);
            }
    
            return response()->json($invoiceItems);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoice items. Please check the invoice ID and try again.'], 500);
        }
    }

    public function update(Request $request, $invoiceItemId)
    {
        try {
            $invoiceItem = InvoiceItem::findOrFail($invoiceItemId);

            $data = $request->validate([
                'item_code' => 'required|string',
                'version' => 'required|integer',
                'item_description_original' => 'required|string',
                'item_description' => 'required|string',
                'quantity' => 'required|integer',
                'base_price' => 'required|numeric',
                'total_price' => 'required|numeric',
                'currency' => 'required|string',
                'best_customs_code_matches' => 'required|array',
            ]);
    
            $invoiceItem->update($data);
    
            return response()->json([
                'message' => 'Invoice item updated successfully.',
                'data' => $invoiceItem
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice item not found. Please check the item ID and try again.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update invoice item: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($invoiceItemId)
    {
        try {
            $invoiceItem = InvoiceItem::findOrFail($invoiceItemId);
            $invoiceItem->delete();
    
            return response()->json(['message' => 'Invoice item deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice item not found. Please check the item ID and try again.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete invoice item. Please try again later.'], 500);
        }
    }
}
