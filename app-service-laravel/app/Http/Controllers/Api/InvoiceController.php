<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $invoices = Invoice::with('items')->get();
            return response()->json($invoices);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoices'], 500);
        }
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::with('items')->findOrFail($id);
            return response()->json($invoice);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    }

    public function getInvoicesBySupplier($supplierId)
    {
        try {
            $invoices = Invoice::where('supplier_id', $supplierId)
                ->with(['items'])
                ->get();

            return response()->json($invoices);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoices'], 500);
        }
    }

    public function getInvoicesByUser($userId)
    {
        try {
            $invoices = Invoice::where('user_id', $userId)
                ->with(['items'])
                ->get();

            return response()->json($invoices);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoices'], 500);
        }
    }

    public function store(Request $request, $userId, $supplierId)
    {
        try {
            // Validate request
            $data = $request->validate([
                'total_price' => 'required|numeric',
                'date_of_issue' => 'required|date',
                'country_of_origin' => 'required|string',
            ]);

            // Ensure supplier exists
            $supplier = Supplier::findOrFail($supplierId);

            // Create invoice
            $invoice = Invoice::create([
                'user_id' => $userId,
                'supplier_id' => $supplier->id,
                'total_price' => $data['total_price'],
                'date_of_issue' => $data['date_of_issue'],
                'country_of_origin' => $data['country_of_origin'],
            ]);

            return response()->json([
                'message' => 'Invoice created successfully',
                'data' => $invoice
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Supplier not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create invoice: ' . $e->getMessage()], 500);
        }
    }

        public function update(Request $request, $invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->update($request->all());

            return response()->json($invoice);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update invoice'], 500);
        }
    }

    public function destroy($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->delete();

            return response()->json(['message' => 'Invoice deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Invoice not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete invoice'], 500);
        }
    }

}
