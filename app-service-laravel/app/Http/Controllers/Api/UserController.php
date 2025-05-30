<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserController extends Controller
{
    /**
     * Get all users.
     */
    public function getAllUsers()
    {
        try {
            $users = User::all();

            return response()->json([
                'message' => 'Users retrieved successfully',
                'users' => $users
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Neuspješno preuzimanje korisnika.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single user by ID.
     */
    public function getUserById($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'message' => 'User retrieved successfully',
                'user' => $user
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Korisnik nije pronađen.',
                'message' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Neuspješno preuzimanje korisnika.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a user by ID.
     */
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'username' => 'nullable|string|unique:users,username,' . $id,
                'email' => 'nullable|email|unique:users,email,' . $id,
                'password' => 'nullable|min:6',
                'avatar' => 'nullable|string',
                'first_name' => 'nullable|string',
                'last_name' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'skills' => 'nullable|array',
                'designation' => 'nullable|string',
                'website' => 'nullable|string',
                'city' => 'nullable|string',
                'country' => 'nullable|string',
                'zip_code' => 'nullable|string',
                'description' => 'nullable|string',
                'company' => 'nullable|array',
                'company.name' => 'nullable|string',
                'company.address' => 'nullable|string',
                'company.id' => 'nullable|string',
                'company.pdv' => 'nullable|string',
                'company.owner' => 'nullable|string',
                'company.contact_person' => 'nullable|string',
                'company.contact_number' => 'nullable|string',
            ]);

            if ($request->filled('password')) {
                $validatedData['password'] = Hash::make($request->password);
            }

            if ($request->has('company')) {
                $validatedData['company'] = $request->input('company');
            }

            $user->update($validatedData);

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Korisnik nije pronađen.',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validacija nije uspjela.',
                'message' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Neuspješno ažuriranje korisnika.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user by ID.
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Korisnik nije pronađen.',
                'message' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Neuspješno brisanje korisnika.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
