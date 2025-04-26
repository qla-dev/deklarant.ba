<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class EmailVerificationController extends Controller
{
    // Step 1: Send verification link
    public function sendVerificationLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Fetch user by email
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->verified) {
            return response()->json(['message' => 'Email is already verified']);
        }

        // Generate signed verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'api.verify.email',
            now()->addMinutes(1440),
            ['id' => $user->id, 'email' => $user->email]
        );

        try {
            Mail::raw("Click the link to verify your email: $verificationUrl", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Email Verification');
            });

            return response()->json(['message' => 'Verification email sent']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }

    // Step 2: Verify the email
    public function verifyEmail(Request $request)
    {
        \Log::info("Verification request received", ['request_data' => $request->all()]);

        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            \Log::error("User not found", ['email' => $email]);
            return response()->json(['error' => 'Invalid verification link'], 404);
        }

        if ($user->verified) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->verified = 1;
        $user->email_verified_at = Carbon::now();
        $user->save();

        \Log::info("User verification updated", ['email' => $email]);

        return response()->json(['message' => 'Email verified successfully']);
    }
}
