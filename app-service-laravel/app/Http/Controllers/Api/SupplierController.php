<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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
        App::setLocale('bs'); 
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'owner' => 'nullable|string',
                'address' => 'nullable|string',
                'avatar' => 'nullable|string',
                'tax_id' => 'nullable|string',
                'contact_email' => 'nullable|string',
                'contact_phone' => 'nullable|string',
                'synonyms' => 'nullable|array',
            ]);
    
            $supplier = $this->model::create($data);
            return response()->json([
                'message' => ucfirst("{$this->label} uspješno sačuvan"),
                'data' => $supplier
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => "Neuspješno sačuvan {$this->label}",
                // ukloniti uglaste zagrade ako treba ispisati sve errore a ne samo prvi
                'message' => $e->errors()[array_key_first($e->errors())][0] 
            ], 422);
        } catch (Exception $e) {
            Log::error("Greška pri spremanju {$this->label}: " . $e->getMessage());
            
            return response()->json([
                'error' => "Neuspješno sačuvan {$this->label}",
                'message' => "Dogodila se greška prilikom spremanja. Molimo pokušajte ponovo"
            ], 500);
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
