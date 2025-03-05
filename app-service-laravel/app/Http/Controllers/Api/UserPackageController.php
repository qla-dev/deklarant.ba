<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPackage;
use Illuminate\Http\Request;

class UserPackageController extends Controller
{
    public function index()
    {
        return response()->json(UserPackage::with('user', 'package')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'active' => 'required|boolean',
            'expiration_date' => 'required|date',
            'remaining_scans' => 'required|integer',
        ]);

        $userPackage = UserPackage::create($data);
        return response()->json($userPackage, 201);
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
