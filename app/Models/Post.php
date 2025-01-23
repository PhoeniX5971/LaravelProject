<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Specify the table if it's not 'posts' by convention
    protected $table = 'posts';

    // Allow mass assignment for relevant columns
    protected $fillable = ['title', 'content', 'user_id', 'username', 'profile_picture', 'bio'];

    // If timestamps are not used, set this to false
    public $timestamps = true;

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Add relationship with collections if needed
    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    // Add relationship for upvotes/downvotes if needed
    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    /**
     * Custom method to simulate user data access.
     * Retrieves associated user data from the same post record.
     */
    public function getUserAttribute()
    {
        return [
            'id' => $this->user_id,
            'username' => $this->username,
            'profile_picture' => $this->profile_picture,
        ];
    }
}
