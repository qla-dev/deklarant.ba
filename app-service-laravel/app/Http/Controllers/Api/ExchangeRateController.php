<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function getRates()
    {
        $today = now()->format('m/d/Y') . ' 00:00:00';
        $url = "https://www.cbbh.ba/CurrencyExchange/GetJson?date=" . urlencode($today);

        $response = Http::get($url);

        return response()->json($response->json());
    }
}
