<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFullDB extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password_hash');
            $table->string('profile_picture')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_post_id')->nullable()->constrained('posts')->onDelete('cascade');
            $table->text('content');
            $table->boolean('has_attachments')->default(false);
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->string('file_url');
            $table->string('file_type', 50);
            $table->timestamp('uploaded_at')->useCurrent();
        });

        Schema::create('post_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('interaction_type');
            $table->timestamps();
            $table->unique(['post_id', 'user_id']);
        });

        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 100);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('collection_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->unique(['collection_id', 'post_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['Post', 'Follow', 'Unfollow', 'Reply', 'Interaction']);
            $table->integer('action_id');
            $table->boolean('is_viewed')->default(false);
            $table->timestamps();
        });

        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('followed_id')->constrained('users')->onDelete('cascade');
            $table->unique(['follower_id', 'followed_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('followers');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('collection_posts');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('post_interactions');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('users');
    }
}