<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $collections = Collection::with('posts')->get();

        return response()->json(
            [
                'success' => true,
                'data' => $collections,
            ]
        );
    }

    /**
     * Create a new collection.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Ensure user exists
        ]);

        $collection = Collection::create([
            'name' => $validated['name'],
            'user_id' => $validated['user_id'],
        ]);

        return response()->json(
            [
                'success' => true,
                'data' => $collection,
            ]
        );
    }

    /**
     * Edit an existing collection.
     *
     * @param Request $request
     * @param int $collectionId
     * @return JsonResponse
     */
    public function edit(Request $request, int $collectionId): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $collection = Collection::find($collectionId);

        if (!$collection) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Collection not found',
                ],
                404
            );
        }

        $collection->update([
            'name' => $validated['name'],
        ]);

        return response()->json(
            [
                'success' => true,
                'data' => $collection,
            ]
        );
    }

    /**
     * Delete a collection.
     *
     * @param int $collectionId
     * @return JsonResponse
     */
    public function delete(int $collectionId): JsonResponse
    {
        $collection = Collection::find($collectionId);

        if (!$collection) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Collection not found',
                ],
                404
            );
        }

        $collection->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Collection deleted successfully',
            ]
        );
    }

    /**
     * Add a post to a collection.
     *
     * @param Request $request
     * @param int $collectionId
     * @return JsonResponse
     */
    public function addPost(Request $request, int $collectionId): JsonResponse
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $collection = Collection::find($collectionId);

        if (!$collection) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Collection not found',
                ],
                404
            );
        }

        $post = Post::find($validated['post_id']);

        if (!$post) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Post not found',
                ],
                404
            );
        }

        // Attach the post to the collection
        $collection->posts()->attach($post);

        return response()->json(
            [
                'success' => true,
                'message' => 'Post added to collection',
            ]
        );
    }

    /**
     * Remove a post from a collection.
     *
     * @param Request $request
     * @param int $collectionId
     * @return JsonResponse
     */
    public function removePost(Request $request, int $collectionId): JsonResponse
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $collection = Collection::find($collectionId);

        if (!$collection) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Collection not found',
                ],
                404
            );
        }

        $post = Post::find($validated['post_id']);

        if (!$post) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Post not found',
                ],
                404
            );
        }

        // Detach the post from the collection
        $collection->posts()->detach($post);

        return response()->json(
            [
                'success' => true,
                'message' => 'Post removed from collection',
            ]
        );
    }
}
