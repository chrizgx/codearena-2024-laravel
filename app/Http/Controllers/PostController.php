<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index(User $user = null, bool $promoted = null)
    {
        $posts = Post::when($user, function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->when($promoted === true, function ($query) {
            $query->where('promoted', true);
        })
        ->whereNotNull('image') // Do not show posts without image in the list
        ->whereNotNull('published_at') // Do not show posts without published date in the list
        ->where('published_at', '<=', now()) // Do not show posts that are scheduled to be published in the future
        ->orderBy('promoted', 'desc') // Show promoted posts first
        ->orderBy('published_at', 'desc')
        ->paginate(12);

        $authors = User::whereHas('posts', function ($query) {
            $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
        })
        ->with(['posts' => function ($query) {
            $query->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'asc');
        }])
        ->get()
        ->sortBy(function ($author) {
            return $author->posts->first()->published_at;
        })
        ->take(3);

        return view('posts.index', compact('posts', 'authors'));
    }

    public function show(Post $post)
    {
        if (!$post->isPublished()) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }
}
