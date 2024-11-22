<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->merge(['post_id' => $post->id]);

        $request->validate([
            'name' => 'required|string|max:32',
            'body' => 'required|string',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
            'reference_id' => 'nullable|exists:comments,id',
        ]);

        $request->merge(['created_at' => now()]);

        // Check if post is published
        $post = Post::find($request->post_id);
        if (!$post->isPublished())  {
            abort(404, 'Post not found or not published');
        }

        // Spam check if comment already exists
        $spam = false;
        
        if ($request->parent_id === null) {
            // If comment is first level, check for first level duplicates
            $spam = Comment::where('post_id', $request->post_id)
            ->where('parent_id', null)
            ->where('body', $request->body)
            ->exists();
        } else {
            // If comment is a reply, check for duplicates that belong to the same parent comment
            $spam = Comment::where('post_id', $request->post_id)
            ->where('parent_id', $request->parent_id)
            ->where('body', $request->body)
            ->exists();
        }
        
        if ($spam) {
            abort(403, 'Duplicate comment');
        };

        // Further checks for replies
        if ($request->parent_id) {
            $parent = Comment::find($request->parent_id);
            if (!$parent) {
                abort(404, 'Parent comment not found');
            }
            if ($parent->post_id != $request->post_id) {
                abort(403, 'Parent comment does not belong to this post');
            }
        }
        
        //  If reference_id is null or equal to parent_id, set it to parent_id with no further checks
        if ($request->parent_id && ( $request->reference_id === null || $request->reference_id === $request->parend_id ) ) {
            $request->reference_id = $request->parent_id;
        } elseif ($request->parent_id && $request->reference_id) {
            $reference = Comment::find($request->reference_id);
            if (!$reference) {
                abort(404, 'Reference comment not found');
            }
            if ($reference->post_id != $request->post_id) {
                abort(403, 'Reference comment does not belong to this post');
            }
        }

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
