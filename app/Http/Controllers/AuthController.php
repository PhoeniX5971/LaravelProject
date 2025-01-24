<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Sign In
//    public function signIn(Request $request)
//    {
//        $credentials = $request->only('email', 'password');
//
//        // Log the incoming credentials to verify
//        \Log::info('Attempting login with:', $credentials);
//
//        if (auth()->attempt($credentials)) {
//            return response()->json(['success' => true, 'message' => 'Login successful']);
//        } else {
//            \Log::info('Authentication failed for email: ' . $request->email);
//            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
//        }
//    }
    public function signIn(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        // Auth::attempt will automatically hash the provided password and compare it to the stored hash
        if (!$token = Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'User signed in successfully',
            'data' => Auth::user(),
            'token' => $token,
        ]);
    }


    // Sign Up (if required)
    public function signUp(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'User signed up successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // Logout
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json(['success' => true, 'message' => 'User logged out']);
    }

    // Get Authenticated User
    public function me(): JsonResponse
    {
        return response()->json(Auth::user());
    }
}
