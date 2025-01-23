<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::select('id', 'username', 'email', 'profile_picture', 'bio')->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Display all posts by a specific user ID.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userPosts(Request $request): JsonResponse
    {
        $userId = $request->query('id');
        $user = User::with('posts')->find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Create a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Ensure password_confirmation is included in the request
            'profile_picture' => 'nullable|url',
            'bio' => 'nullable|string',
        ]);

        // Create the user
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'profile_picture' => $validated['profile_picture'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        // Return a response
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    /**
     * Edit user info.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function edit(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'username' => 'string|max:255|unique:users,username,' . $user->id,
            'email' => 'email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'username' => $request->username ?? $user->username,
            'email' => $request->email ?? $user->email,
            'profile_picture' => $request->profile_picture ?? $user->profile_picture,
            'bio' => $request->bio ?? $user->bio,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    /**
     * Sign in a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signIn(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User signed in successfully',
                'data' => $user,
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * Log out a user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }
}
