<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    public function registerUser(Request $request)
    {
        // Check if the user is already logged in
        $isLoggedResponse = $this->isLogged($request);
        if ($isLoggedResponse) {
            return $isLoggedResponse;
        }

        try {
            // Retrieve MAC address from request headers
            $macAddress = $request->header('MAC-Address');

            // Validate input data
            $validatedData = $request->validate([
                'email'     => 'required|email|unique:users,email',
                'username'  => 'required|string|unique:users,username',
                'password'  => 'required|min:6',
                'confirm_password' => 'required|min:6',
                'avatar'    => 'nullable|string|ends_with:.jpg,.jpeg,.png', // Only jpg allowed

                 // Only jpg allowed

            ]);

            if ($validatedData['password'] !== $validatedData['confirm_password']) {
                return response()->json(['error' => 'Password confirmation does not match.'], 400);
            }

            $userResponse = User::create([
                'email'    => $validatedData['email'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'avatar'   => $validatedData['avatar'] ?? null, // fallback to null if not set
            ]);
            

            // Proceed to login the user
            $loginRequest = new Request([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'from_registration' => true,
            ]);
            
            $loginRequest->headers->set('MAC-Address', $macAddress);
            
            return $this->login($loginRequest);

        } catch (ModelNotFoundException $e) {
            Log::error("User not found after registration: " . $e->getMessage());
            return response()->json(['error' => 'User not found. Please try again.'], 404);
        }catch (Exception $e) {
            Log::error("Registration error: " . $e->getMessage());
            return response()->json(["error" => "User registration failed.", "message" => $e->getMessage()], 500);
        }
    }



public function login(Request $request)
{
    // Retrieve MAC address from request headers
    $macAddress = $request->header('MAC-Address');

    // Check if the user is already logged in from the same device
    $isLoggedFromSameDevice = $this->isLoggedFromSameDevice($request);
    if ($isLoggedFromSameDevice) {
        return $isLoggedFromSameDevice;
    }

    $identifier = $request->has('username') ? 'username' : 'email';

    $request->validate([
        $identifier => 'required|string',
        'password'  => 'required|string',
    ]);

    $user = User::where('email', $request->input($identifier))
                ->orWhere('username', $request->input($identifier))
                ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid login credentials.'], 401);
    }

    //  Log in via session (optional, for web routes using 'web' guard)
    Auth::login($user);

    // Create API token for Sanctum usage
    $token = $user->createToken('auth_token')->plainTextToken;

    // Store MAC address in personal_access_tokens table
    DB::table('personal_access_tokens')
        ->where('tokenable_id', $user->id)
        ->latest()
        ->update(['device' => $macAddress]);

    Log::info("User {$user->email} logged in. MAC: {$macAddress} Token: {$token}");

    // From registration flag
    $isFromRegistration = $request->input('from_registration', false);

    return response()->json([
        'message' => $isFromRegistration ? 'Registration and Login successful.' : 'Login successful',
        'token' => $token,
        'macAddress' => $macAddress,
        'user' => $user->only(['id', 'username', 'role', 'email', 'avatar']),
    ], 200);
}


    public function logout(Request $request)
    {
        // Log out from session (web guard)
        Auth::logout();
        // Delete current access token (API guard)
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out.'], 200);
    }

    public function myToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'No token found in the request.'], 400);
        }

        $currentToken = PersonalAccessToken::findToken($token);

        if (!$currentToken) {
            return response()->json(['message' => 'Token not found or invalid.'], 401);
        }

        return response()->json(['token_data' => $currentToken, 'current_token' => $token]);
    }

    private function isLogged(Request $request)
    {
        $token = $request->bearerToken();
        $currentToken = PersonalAccessToken::findToken($token);

        if ($currentToken) {
            return response()->json(['message' => 'You are already logged in.'], 401);
        }

        return null;
    }

    private function isLoggedFromSameDevice(Request $request)
    {
        $macAddress = $request->header('MAC-Address');
        if (!$macAddress) {
            return null; // Can't check without MAC Address
        }

        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        $currentToken = PersonalAccessToken::findToken($token);
        if (!$currentToken) {
            return null;
        }

        $existingToken = PersonalAccessToken::where('tokenable_id', $currentToken->tokenable_id)
            ->where('device', $macAddress)
            ->first();

        if ($existingToken) {
            return response()->json([
                'message' => 'User is already logged in from this device.',
                'device' => $macAddress
            ], 409);
        }

        return null;
    }

}

