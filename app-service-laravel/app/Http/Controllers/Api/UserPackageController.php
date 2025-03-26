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
                'message' => 'User packages retrieved successfully.',
                'data' => $userPackages
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve user packages. Please try again later.'], 500);
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
                'message' => 'User package assigned successfully.',
                'data' => $userPackage
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User or Package not found. Please check the IDs and try again.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to assign user package: ' . $e->getMessage()], 500);
        }
    }

    public function show($userPackageId)
    {
        try {
            $userPackage = UserPackage::with('user', 'package')->findOrFail($userPackageId);
            return response()->json([
                'message' => 'User package retrieved successfully.',
                'data' => $userPackage
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User package not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve user package. Please try again later.'], 500);
        }
    }

    public function update(Request $request, $userPackageId)
    {
        try {
            $userPackage = UserPackage::findOrFail($userPackageId);
            $userPackage->update($request->all());
            return response()->json([
                'message' => 'User package updated successfully.',
                'data' => $userPackage
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User package not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update user package. Please check the data and try again.'], 500);
        }
    }

    public function destroy($userPackageId)
    {
        try {
            $userPackage = UserPackage::findOrFail($userPackageId);
            $userPackage->delete();
            return response()->json(['message' => 'User package deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User package not found with the given ID.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete user package. Please try again later.'], 500);
        }
    }
}
