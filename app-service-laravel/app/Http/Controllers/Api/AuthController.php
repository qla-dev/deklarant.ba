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
use Illuminate\Validation\ValidationException;
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
                return response()->json(['error' => 'Potvrda lozinke se ne podudara'], 400);
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
            Log::error("Korisnik nije pronađen nakon registracije: " . $e->getMessage());
            return response()->json(['error' => 'Korisnik nije pronađen. Pokušajte ponovo kasnije'], 404);
        }catch (ValidationException $e) {
            return response()->json([
                'error' => "Registracija korisnika neuspješna.",
                // ukloniti uglaste zagrade ako treba ispisati sve errore a ne samo prvi
                'message' => $e->errors()[array_key_first($e->errors())][0] 
            ], 422);
        }catch (Exception $e) {
            Log::error("Greška pri registraciji korisnika: " . $e->getMessage());

            return response()->json([
                'error' => 'Registracija korisnika neuspješna.',
                'message' => 'Došlo je do greške prilikom registracije. Molimo pokušajte ponovo.'
            ], 500);
        }

    }



public function login(Request $request)
{
    $macAddress = $request->header('MAC-Address');

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
        return response()->json(['message' => 'Neispravni pristupni podaci'], 401);
    }

    // Log in via session (for web routes)
    Auth::login($user);

    // Generate token
    $tokenResult = $user->createToken('auth_token');
    $token = $tokenResult->plainTextToken;

    // Save token in session
    session(['auth_token' => $token]);

    // Store MAC address in token table
    DB::table('personal_access_tokens')
        ->where('tokenable_id', $user->id)
        ->latest()
        ->update(['device' => $macAddress]);

    Log::info("User {$user->email} logged in. MAC: {$macAddress} Token: {$token}");

    $isFromRegistration = $request->input('from_registration', false);

    // Determine redirect URL based on user role
    $redirectUrl = ($user->role === 'superadmin') ? '/' : '/';

    return response()->json([
        'message' => $isFromRegistration ? 'Registracija i prijava uspješne' : 'Prijava uspješna',
        'token' => $token,
        'macAddress' => $macAddress,
        'user' => $user->only(['id', 'username', 'role', 'email', 'avatar']),
        'redirect' => $redirectUrl,
    ], 200);

}


    public function logoutUser(Request $request)
    {
        // Delete all access tokens for the authenticated user
        $userId = Auth::id();
        if ($userId) {
            PersonalAccessToken::where('tokenable_id', $userId)->delete();
        }

        // Log out from session (web guard)
        Auth::logout();

        // Invalidate the session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Forget the session cookie
        $cookieName = config('session.cookie');
        $cookie = cookie($cookieName, null, -1);

        // Redirect to /
        return redirect('/')->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        // Get token from Authorization header
        $token = $request->bearerToken();
        session()->forget('auth_token');

        if ($token) {
            $currentToken = PersonalAccessToken::findToken($token);
            if ($currentToken) {
                $currentToken->delete();
                return response()->json(['message' => 'Odjavljeni ste iz API dijela']);
            }
        }

        return response()->json(['message' => 'Nije pronađen važeći token'], 400);
    }

    public function myToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token nije pronađen u zahtjevu'], 400);
        }

        $currentToken = PersonalAccessToken::findToken($token);

        if (!$currentToken) {
            return response()->json(['message' => 'Token nije pronađen ili je nevažeći'], 401);
        }

        return response()->json(['token_data' => $currentToken, 'current_token' => $token]);
    }

    private function isLogged(Request $request)
    {
        $token = $request->bearerToken();
        $currentToken = PersonalAccessToken::findToken($token);

        if ($currentToken) {
            return response()->json(['message' => 'Već ste prijavljeni'], 401);
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
                'message' => 'Korisnik je već prijavljen sa ovog uređaja',
                'device' => $macAddress
            ], 409);
        }

        return null;
    }

}

