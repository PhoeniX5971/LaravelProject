<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
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
        $users = User::select('id', 'username', 'email', 'profile_picture', 'bio', 'password')->get();

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
//    public function userPosts(Request $request): JsonResponse
//    {
//        $userId = $request->query('id');
//        $user = User::with('posts')->find($userId);
//
//        if (!$user) {
//            return response()->json([
//                'success' => false,
//                'message' => 'User not found',
//            ], 404);
//        }
//
//        return response()->json([
//            'success' => true,
//            'data' => $user,
//        ]);
//    }
    public function userPosts(): JsonResponse
    {
        try {
            // Verify token
            $user = JWTAuth::parseToken()->authenticate();

            // If the token is valid, return the posts for the user
            if ($user) {
                $posts = Post::where('user_id', $user->id)->get();

                return response()->json([
                    'success' => true,
                    'data' => $posts,
                ]);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid or expired',
            ], 401);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ]);
    }

    /**
     * Create a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|url',
            'bio' => 'nullable|string',
        ]);

        try {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password_hash' => bcrypt($validated['password']),
                'profile_picture' => $validated['profile_picture'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]);

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


//    /**
//     * Create a new user.
//     *
//     * @param Request $request
//     * @return JsonResponse
//     */
//    public function create(Request $request): JsonResponse
//    {
//        // Validate the incoming request
//        $validated = $request->validate([
//            'username' => 'required|string|max:255',
//            'email' => 'required|email|unique:users,email',
//            'password' => 'required|string|min:8|confirmed', // Ensure password_confirmation is included in the request
//            'profile_picture' => 'nullable|url',
//            'bio' => 'nullable|string',
//        ]);
//
//        try {
//            // Create the user
//            $user = User::create([
//                'username' => $validated['username'],
//                'email' => $validated['email'],
//                'password_hash' => bcrypt($validated['password']),
//                'profile_picture' => $validated['profile_picture'] ?? null,
//                'bio' => $validated['bio'] ?? null,
//            ]);
//
//            // Return a success response
//            return response()->json([
//                'message' => 'User created successfully',
//                'user' => $user
//            ], 201);
//        } catch (\Exception $e) {
//            // Handle any exceptions that may occur
//            return response()->json([
//                'message' => 'User creation failed',
//                'error' => $e->getMessage()
//            ], 500);
//        }
//    }

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

//    public function signIn(Request $request): JsonResponse
//    {
//        $credentials = $request->only('email', 'password');
//
//        // Auth::attempt will automatically hash the provided password and compare it to the stored hash
//        if (!$token = Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Invalid credentials',
//            ], 401);
//        }
//
//        return response()->json([
//            'success' => true,
//            'message' => 'User signed in successfully',
//            'data' => Auth::user(),
//            'token' => $token,
//        ]);
//    }


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
