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
    public function signIn(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'User signed in',
            'token' => $token,
            'user' => Auth::user(),
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
