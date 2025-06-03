<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPackage;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserPackageController extends Controller
{
    public function index()
    {
        try {
            $userPackages = UserPackage::with('user', 'package')->get();
            return response()->json([
                'message' => 'Korisnički paketi uspješno preuzeti',
                'data' => $userPackages
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno preuzimanje korisničkih paketa. Pokušajte ponovo kasnije'], 500);
        }
    }

    public function store(Request $request, $userId, $packageId)
    {
        try {
            $request->validate([
                'active' => 'required|boolean',
                'expiration_date' => 'required|date'
            ]);
    
            $user = User::findOrFail($userId);
            $package = Package::findOrFail($packageId);
    
            $userPackage = UserPackage::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'active' => $request->input('active'),
                'expiration_date' => $request->input('expiration_date'),
                'remaining_scans' => $package->available_scans,
            ]);
    
            return response()->json([
                'message' => 'Korisnički paket uspješno dodijeljen',
                'data' => $userPackage
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Korisnik ili paket nije pronađen. Provjerite ID-ove i pokušajte ponovo'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno dodjeljivanje korisničkog paketa: ' . $e->getMessage()], 500);
        }
    }

    public function show($userPackageId)
    {
        try {
            $userPackage = UserPackage::with('user', 'package')->findOrFail($userPackageId);
            return response()->json([
                'message' => 'Korisnički paket uspješno preuzet',
                'data' => $userPackage
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Korisnički paket s unesenim ID-om nije pronađen'], 404);
        } catch (Exception $e) {
            return response()->json(['Neuspješno preuzimanje korisničkog paketa. Pokušajte ponovo kasnije'], 500);
        }
    }

    public function update(Request $request, $userId)
    {
        try {
            $userPackage = UserPackage::where('user_id', $userId)->firstOrFail();
            $userPackage->update($request->all());
            return response()->json([
                'message' => 'Korisnički paket uspješno ažuriran',
                'data' => $userPackage
            ]);

            
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Korisnički paket s unesenim ID-om nije pronađen'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno ažuriranje korisničkog paketa. Provjerite podatke i pokušajte ponovo'], 500);
        }
    }

    public function destroy($userPackageId)
    {
        try {
            $userPackage = UserPackage::findOrFail($userPackageId);
            $userPackage->delete();
            return response()->json(['message' => 'Korisnički paket uspješno izbrisan']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Korisnički paket s unesenim ID-om nije pronađen'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Neuspješno brisanje korisničkog paketa. Pokušajte ponovo kasnije'], 500);
        }
    }
}
