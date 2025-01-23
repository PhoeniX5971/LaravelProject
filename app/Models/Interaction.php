<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    // Specify the table if it's not 'interactions' by convention
    protected $table = 'interactions';

    // Allow mass assignment for relevant columns
    protected $fillable = ['user_id', 'post_id', 'interaction_type']; // interaction_type could be 'upvote' or 'downvote'

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
