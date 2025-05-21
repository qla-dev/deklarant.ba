<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class SupplierController extends Controller
{
    protected $model = Supplier::class;

    protected string $label = 'supplier';
    protected string $labelPlural = 'suppliers';


    public function index()
    {
        try {
            $suppliers = $this->model::all();
            return response()->json([
                'message' => "{$this->labelPlural} retrieved successfully.",
                'data' => $suppliers
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to retrieve {$this->labelPlural}. Please try again later."], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'owner' => 'required|string',
                'address' => 'required|string',
                'avatar' => 'nullable|string',
                'tax_id' => 'required|string|unique:suppliers,tax_id',
                'contact_email' => 'required|email|unique:suppliers,contact_email',
                'contact_phone' => 'required|string|unique:suppliers,contact_phone',
                'synonyms' => 'nullable|array',
            ]);
    
            $supplier = $this->model::create($data);
            return response()->json([
                'message' => "{$this->label} stored successfully.",
                'data' => $supplier
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to store {$this->label}: " . $e->getMessage()], 500);
        }
    }

    public function show($supplierId)
    {
        try {
            $supplier = $this->model::findOrFail($supplierId);
            return response()->json($supplier);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "{$this->label} not found with the given ID."], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to retrieve {$this->label}. Please try again later."], 500);
        }
    }

    public function update(Request $request, $supplierId)
    {
        try {
            $supplier = $this->model::findOrFail($supplierId);
            $supplier->update($request->all());
            return response()->json([
                'message' => "{$this->label} updated successfully.",
                'data' => $supplier
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "{$this->label} not found with the given ID."], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to update {$this->label}. Please check the data and try again."], 500);
        }
    }

    public function destroy($supplierId)
    {
        try {
            $supplier = $this->model::findOrFail($supplierId);
            $supplier->delete();
            return response()->json(['message' => "{$this->label} deleted successfully."]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "{$this->label} not found with the given ID."], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to delete {$this->label}. Please try again later."], 500);
        }
    }
}
