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

    protected string $label = 'dobavljač';
    protected string $labelPlural = 'dobavljači';


    public function index()
    {
        try {
            $suppliers = $this->model::all();
            return response()->json([
                'message' => ucfirst("{$this->labelPlural} uspješno preuzeti"),
                'data' => $suppliers
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => "Neuspješno preuzeti {$this->labelPlural}. Pokušajte ponovo kasnije"], 500);
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
                'message' => ucfirst("{$this->label} uspješno sačuvan"),
                'data' => $supplier
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => "Neuspješno sačuvan {$this->label}: " . $e->getMessage()], 500);
        }
    }

    public function show($supplierId)
    {
        try {
            $supplier = $this->model::findOrFail($supplierId);
            return response()->json($supplier);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => ucfirst("{$this->label} nije pronađen s unesenim ID-om")], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Neuspješno preuzet {$this->label}. Pokušajte ponovo kasnije"], 500);
        }
    }

    public function update(Request $request, $supplierId)
    {
        try {
            $supplier = $this->model::findOrFail($supplierId);
            $supplier->update($request->all());
            return response()->json([
                'message' => "{$this->label} uspješno ažuriran",
                'data' => $supplier
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => ucfirst("{$this->label} nije pronađen s unesenim ID-om")], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Neuspješno ažuriran {$this->label}. Pokušajte ponovo kasnije"], 500);
        }
    }

    public function destroy($supplierId)
    {
        try {
            $supplier = $this->model::findOrFail($supplierId);
            $supplier->delete();
            return response()->json(['message' => ucfirst("{$this->label} uspješno izbrisan")]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => ucfirst("{$this->label} nije pronađen s unesenim ID-om")], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Neuspješno izbrisan {$this->label}. Pokušajte ponovo kasnije"], 500);
        }
    }
}
