<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class SupplierController extends Controller
{
    public function index()
    {
        try {
            $suppliers = Supplier::all();
            return response()->json([
                'message' => 'Suppliers retrieved successfully.',
                'data' => $suppliers
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve suppliers. Please try again later.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'avatar' => 'nullable|string',
                'tax_id' => 'required|string|unique:suppliers,tax_id',
                'contact_email' => 'required|email|unique:suppliers,contact_email',
                'contact_phone' => 'required|string|unique:suppliers,contact_phone',
            ]);
    
            $supplier = Supplier::create($data);
            return response()->json([
                'message' => 'Supplier stored successfully.',
                'data' => $supplier
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to store supplier: ' . $e->getMessage()], 500);
        }
    }

    public function show($supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);
            return response()->json($supplier);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Supplier not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve supplier. Please try again later.'], 500);
        }
    }

    public function update(Request $request, $supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);
            $supplier->update($request->all());
            return response()->json([
                'message' => 'Supplier updated successfully.',
                'data' => $supplier
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Supplier not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update supplier. Please check the data and try again.'], 500);
        }
    }

    public function destroy($supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);
            $supplier->delete();
            return response()->json(['message' => 'Supplier deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Supplier not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete supplier. Please try again later.'], 500);
        }
    }
}
