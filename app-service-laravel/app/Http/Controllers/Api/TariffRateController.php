<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TariffRate;
use Illuminate\Http\Request;

class TariffRateController extends Controller
{
    public function index()
    {
        return response()->json(TariffRate::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_code' => 'required|string|unique:tariff_rates,item_code',
            'name' => 'required|string',
            'tariff_rate' => 'required|string',
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
        return response()->json($tariffRate, 201);
    }
}
