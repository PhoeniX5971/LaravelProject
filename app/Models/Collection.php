<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class Collection extends Model
{
    // Specify the table if it's not 'collections' by convention
    protected $table = 'collections';

    // Allow mass assignment for relevant columns
    protected $fillable = ['name', 'user_id'];

    // If timestamps are not used, set this to false
    public $timestamps = true;

    // Define relationships
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
