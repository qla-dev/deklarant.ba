<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PackageController extends Controller
{
    public function index()
    {
        try {
            $packages = Package::all()->makeHidden(['created_at', 'updated_at']);
            return response()->json([
                'message' => 'Packages retrieved successfully.',
                'data' => $packages
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje paketa. Pokušajte ponovo kasnije.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'available_scans' => 'required|integer',
                'validity_days' => 'required|integer',
            ]);

            $package = Package::create($data);
            return response()->json([
                'message' => 'The package has been stored successfully.',
                'data' => $package
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno spremanje paketa: ' . $e->getMessage()], 500);
        }
    }

    public function show($packageId)
    {
        try {
            $package = Package::findOrFail($packageId);
            return response()->json($package);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Paket s unesenim ID-om nije pronađen.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje paketa. Pokušajte ponovo kasnije.'], 500);
        }

    }

    public function update(Request $request, $packageId)
    {
        try {
            $package = Package::findOrFail($packageId);
            $package->update($request->all());
            return response()->json([
                'message' => 'Package updated successfully.',
                'data' => $package
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Paket s unesenim ID-om nije pronađen.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno ažuriranje paketa. Provjerite podatke i pokušajte ponovo.'], 500);
        }

    }

    public function destroy($packageId)
    {
        try {
            $package = Package::findOrFail($packageId);
            $package->delete();
            return response()->json(['message' => 'Package deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Paket s unesenim ID-om nije pronađen.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno brisanje paketa. Pokušajte ponovo kasnije.'], 500);
        }

    }
}
