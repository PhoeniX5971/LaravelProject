<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::all(); // Fetch all posts

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $post = Post::create($request->all());
        return response()->json(['success' => true, 'data' => $post]);
    }

    public function edit(Post $post, Request $request): JsonResponse
    {
        $post->update($request->all());
        return response()->json(['success' => true, 'data' => $post]);
    }

    public function delete(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(['success' => true, 'message' => 'Post deleted successfully']);
    }

    // User interaction methods (Upvote, Downvote)
    public function upvote(Post $post): JsonResponse
    {
        // Implement upvote logic
        return response()->json(['success' => true]);
    }

    public function downvote(Post $post): JsonResponse
    {
        // Implement downvote logic
        return response()->json(['success' => true]);
    }

    public function removeInteraction(Post $post): JsonResponse
    {
        // Implement removing interaction logic
        return response()->json(['success' => true]);
    }
}
