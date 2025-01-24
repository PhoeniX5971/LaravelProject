<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Custom;
use App\Http\Middleware\HandlePreflight;


Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('users/create', [AuthController::class, 'signUp'])->name('users.create'); // Create a new user
Route::post('users/sign-in', [AuthController::class, 'signIn'])->middleware([HandlePreflight::class])->name('login'); // Sign-in user
Route::get('posts/', [PostController::class, 'index'])->name('posts.index')-> middleware([HandlePreflight::class]); // Get all posts


// User-related routes
Route::prefix('users')->middleware(['auth:jwt', HandlePreflight::class])->group(function () {
    // Route::get('/', [UserController::class, 'index'])->name('users.index'); // Get all users
    Route::put('/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Edit user info
    Route::delete('/{user}', [UserController::class, 'delete'])->name('users.delete'); // Delete user
    Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout'); // Logout user
});


// Post-related routes
Route::prefix('posts')->middleware(['auth:jwt', HandlePreflight::class])->group(function () {
//    Route::get('/', [PostController::class, 'index'])->name('posts.index'); // Get all posts
    Route::get('/my-posts', [UserController::class, 'userPosts'])->name('posts.my-posts');
    Route::post('/create', [PostController::class, 'create'])->name('posts.create'); // Create a post
    Route::put('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // Edit a post
    Route::delete('/{post}', [PostController::class, 'delete'])->name('posts.delete'); // Delete a post
});

// Collection-related routes
Route::prefix('collections')->middleware(HandlePreflight::class)->group(function () {
    Route::get('/', [CollectionController::class, 'index'])->name('collections.index'); // Get all collections
    Route::post('/create', [CollectionController::class, 'create'])->name('collections.create'); // Create a collection
    Route::put('/{collection}/edit', [CollectionController::class, 'edit'])->name('collections.edit'); // Edit a collection
    Route::delete('/{collection}', [CollectionController::class, 'delete'])->name('collections.delete'); // Delete a collection
    Route::post('/{collection}/add-post', [CollectionController::class, 'addPost'])->name('collections.add-post'); // Add post to collection
    Route::delete('/{collection}/remove-post', [CollectionController::class, 'removePost'])->name('collections.remove-post'); // Remove post from collection
});

// User interaction routes (upvote/downvote)
Route::prefix('interactions')->middleware(HandlePreflight::class)->group(function () {
    Route::post('/{post}/upvote', [PostController::class, 'upvote'])->name('interactions.upvote'); // Upvote post
    Route::post('/{post}/downvote', [PostController::class, 'downvote'])->name('interactions.downvote'); // Downvote post
    Route::delete('/{post}/remove', [PostController::class, 'removeInteraction'])->name('interactions.remove'); // Remove interaction (upvote/downvote)
});
