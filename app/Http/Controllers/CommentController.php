<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:32',
            'body' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        $request->merge(['created_at' => now()]);

        // Check if post is published
        $post = Post::find($request->post_id);
        if (!$post->isPublished())  {
            abort(404, 'Post not found or not published');
        }
        // Spam check if comment already exists
        $spam = Comment::where('post_id', $request->post_id)
            ->where('body', $request->body)
            ->exists();
        
        if ($spam) {
            abort(403, 'Duplicate comment');
        };
        

        // Save comment
        $comment = Comment::create($request->all());

        return redirect()->route('post', ['post' => $post->slug]);
    }

    public function delete(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('post', ['post' => $comment->post->slug]);
    }
}
