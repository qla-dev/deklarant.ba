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
            return response()->json(['error' => 'Neuspješno preuzimanje stavki deklaracije. Pokušajte ponovo kasnije'], 500);
        }
    }

    public function store(Request $request, $invoice_id)
    {
        try {
            $data = $request->validate([
                'item_code' => 'required|string',
                'version' => 'required|integer',
                'item_description_original' => 'required|string',
                'item_description_translated' => 'required|string',
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
                'message' => 'Stavka deklaracije uspješno kreirana',
                'data' => $invoiceItem
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deklaracija nije pronađena. Provjerite ID deklaracije i pokušajte ponovo'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno kreiranje stavke deklaracije. Pokušajte ponovo kasnije'], 500);
}

    }
    
    public function getInvoiceItemsSingleInvoice($invoiceId)
    {
        try {
            $invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();
    
            if ($invoiceItems->isEmpty()) {
                return response()->json(['error' => 'Nisu pronađene stavke za navedenu deklaraciju'], 404);
            }
    
            return response()->json($invoiceItems);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje stavki deklaracije. Provjerite ID deklaracije i pokušajte ponovo'], 500);
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
                'item_description_translated' => 'required|string',
                'item_description' => 'required|string',
                'quantity' => 'required|integer',
                'base_price' => 'required|numeric',
                'total_price' => 'required|numeric',
                'currency' => 'required|string',
                'best_customs_code_matches' => 'required|array',
            ]);
    
            $invoiceItem->update($data);
    
            return response()->json([
                'message' => 'Invoice item updated successfully',
                'data' => $invoiceItem
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Stavka deklaracije nije pronađena. Provjerite ID stavke i pokušajte ponovo'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno ažuriranje stavke deklaracije. Pokušajte ponovo kasnije'], 500);
        }

    }

    public function destroy($invoiceItemId)
    {
        try {
            $invoiceItem = InvoiceItem::findOrFail($invoiceItemId);
            $invoiceItem->delete();
    
            return response()->json(['message' => 'Stavka deklaracije uspješno izbrisana']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Stavka deklaracije nije pronađena. Provjerite ID stavke i pokušajte ponovo'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno brisanje stavke deklaracije. Pokušajte ponovo kasnije'], 500);
        }
    }
}
