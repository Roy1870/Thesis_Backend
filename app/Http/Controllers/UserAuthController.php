<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserAuthController extends Controller
{
    /**
     * Login the user and return an authentication token.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        // Manually retrieve the user based on email
        $user = User::where('email', $credentials['email'])->first();

        // Check if the user exists and if the password is correct
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Generate a token for the user (Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json(['message' => 'Invalid email or password'], 401);
    }

    /**
     * Logout the user (invalidate the token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful'], 200);
    }

    public function getUserInfo(Request $request)
{
    // Retrieve the currently authenticated user based on the token
    $user = auth()->user();  // or $request->user()

    return response()->json([
        'user' => $user,
    ]);
}

}
