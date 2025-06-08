<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TariffRate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TariffRateController extends Controller
{
    public function index()
    {
        try {
            $tariffRates = TariffRate::all();
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje carinskih stopa. Pokušajte ponovo kasnije'], 500);
        }
        return response()->json([
            'message' => 'Carinske stope uspješno preuzete',
            'data' => $tariffRates
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'item_code' => 'required|string|unique:tariff_rates,item_code',
                'version' => 'required|integer',
                'name' => 'required|string',
                'tariff_rate' => 'required|string',
                'supplementary_unit' => 'nullable|string',
                'EU' => 'nullable|string',
                'CEFTA' => 'nullable|string',
                'IRN' => 'nullable|string',
                'TUR' => 'nullable|string',
                'CHE_LIE' => 'nullable|string',
                'ISL' => 'nullable|string',
                'NOR' => 'nullable|string',
                'section' => 'required|string',
                'head' => 'required|string',
                'english_name' => 'required|string',
            ]);
    
            $tariffRate = TariffRate::create($data);
            return response()->json([
                'message' => 'Carinska stopa uspješno kreirana',
                'data' => $tariffRate
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno kreiranje carinske stope. Pokušajte ponovo kasnije'], 500);
        }
    }

    public function show($tariffRateId)
    {
        try {
            $tariffRate = TariffRate::findOrFail($tariffRateId);
            return response()->json([
                'message' => 'Carinska stopa uspješno preuzeta',
                'data' => $tariffRate
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Carinska stopa s unesenim ID-om nije pronađena'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje carinske stope. Pokušajte ponovo kasnije'], 500);
        }

    }

    public function update(Request $request, $tariffRateId)
    {
        try {
            $tariffRate = TariffRate::findOrFail($tariffRateId);
    
            $data = $request->validate([
                'item_code' => 'sometimes|string|unique:tariff_rates,item_code,' . $tariffRate->id,
                'version' => 'required|integer',
                'name' => 'sometimes|string',
                'tariff_rate' => 'sometimes|string',
                'supplementary_unit' => 'nullable|string',
                'EU' => 'nullable|string',
                'CEFTA' => 'nullable|string',
                'IRN' => 'nullable|string',
                'TUR' => 'nullable|string',
                'CHE_LIE' => 'nullable|string',
                'ISL' => 'nullable|string',
                'NOR' => 'nullable|string',
                'section' => 'sometimes|string',
                'head' => 'sometimes|string',
                'english_name' => 'sometimes|string',
            ]);
    
            $tariffRate->update($data);
    
            return response()->json([
                'message' => 'Carinska stopa uspješno ažurirana',
                'data' => $tariffRate
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Carinska stopa s unesenim ID-om nije pronađena'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno ažuriranje carinske stope. Provjerite podatke i pokušajte ponovo'], 500);
        }
    }

    public function destroy($tariffRateId)
    {
        try {
            $tariffRate = TariffRate::findOrFail($tariffRateId);
            $tariffRate->delete();
            return response()->json(['message' => 'carinska stopa uspješno izbrisana']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Carinska stopa s unesenim ID-om nije pronađena'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno brisanje carinske stope. Pokušajte ponovo kasnije'], 500);
        }

    }
}
