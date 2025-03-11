<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TariffRate;
use Illuminate\Http\Request;

class TariffRateController extends Controller
{
    /**
     * Display a listing of tariff rates.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Tariff rates retrieved successfully',
            'data' => TariffRate::all()
        ]);
    }

    /**
     * Store a newly created tariff rate in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'item_code' => 'required|string|unique:tariff_rates,item_code',
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
            'message' => 'Tariff rate created successfully',
            'data' => $tariffRate
        ], 201);
    }

    /**
     * Display the specified tariff rate.
     */
    public function show(TariffRate $tariffRate)
    {
        return response()->json([
            'message' => 'Tariff rate retrieved successfully',
            'data' => $tariffRate
        ]);
    }

    /**
     * Update the specified tariff rate in storage.
     */
    public function update(Request $request, TariffRate $tariffRate)
    {
        $data = $request->validate([
            'item_code' => 'sometimes|string|unique:tariff_rates,item_code,' . $tariffRate->id,
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
            'message' => 'Tariff rate updated successfully',
            'data' => $tariffRate
        ]);
    }

    /**
     * Remove the specified tariff rate from storage.
     */
    public function destroy(TariffRate $tariffRate)
    {
        $tariffRate->delete();
        return response()->json([
            'message' => 'Tariff rate deleted successfully'
        ]);
    }
}
