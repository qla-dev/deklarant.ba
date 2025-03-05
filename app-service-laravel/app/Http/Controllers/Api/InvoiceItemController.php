<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    public function index()
    {
        return response()->json(InvoiceItem::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'item_code' => 'required|string',
            'item_description_original' => 'required|string',
            'item_description' => 'required|string',
            'quantity' => 'required|integer',
            'base_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'currency' => 'required|string',
            'best_customs_code_matches' => 'required|json',
        ]);

        $invoiceItem = InvoiceItem::create($data);
        return response()->json($invoiceItem, 201);
    }

    public function show(InvoiceItem $invoiceItem)
    {
        return response()->json($invoiceItem);
    }

    public function update(Request $request, InvoiceItem $invoiceItem)
    {
        $invoiceItem->update($request->all());
        return response()->json($invoiceItem);
    }

    public function destroy(InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete();
        return response()->json(['message' => 'Invoice Item deleted']);
    }
}
