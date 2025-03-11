<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPackage;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;

class UserPackageController extends Controller
{
    public function index()
    {
        return response()->json(UserPackage::with('user', 'package')->get());
    }

    public function store(Request $request, $userId, $packageId)
{
    // Validate input (optional, depends on your needs)
    $request->validate([
        'active' => 'required|boolean',
        'expiration_date' => 'required|date'
    ]);

    // Check if user and package exist
    $user = User::findOrFail($userId);
    $package = Package::findOrFail($packageId);

    // Create a new UserPackage record
    $userPackage = UserPackage::create([
        'user_id' => $user->id,
        'package_id' => $package->id,
        'active' => $request->input('active'),
        'expiration_date' => $request->input('expiration_date'),
        'remaining_scans' => $package->available_scans, // Assign initial scan limit from package
    ]);

    // Return response
    return response()->json([
        'message' => 'User package assigned successfully',
        'data' => $userPackage
    ], 201);
}


    public function show(UserPackage $userPackage)
    {
        return response()->json($userPackage->load('user', 'package'));
    }

    public function update(Request $request, UserPackage $userPackage)
    {
        $userPackage->update($request->all());
        return response()->json($userPackage);
    }

    public function destroy(UserPackage $userPackage)
    {
        $userPackage->delete();
        return response()->json(['message' => 'User Package deleted']);
    }
}
