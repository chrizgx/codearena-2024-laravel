<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index(User $user = null)
    {
        $posts = Post::when($user, function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereNotNull('image') // Do not show posts without image in the list
        ->whereNotNull('published_at') // Do not show posts without published date in the list
        ->where('published_at', '<=', now()) // Do not show posts that are scheduled to be published in the future
        ->orderBy('published_at', 'desc')
        ->paginate(12);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if (!$post->isPublished()) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }
}
