<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['name', 'body', 'post_id', 'created_at'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
