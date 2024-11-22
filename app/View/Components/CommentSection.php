<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Comment;
use App\Models\Post;

class CommentSection extends Component
{
    /**
     * Create a new component instance.
     */

    public Collection $comments;
    public Post $post;
    
    public function __construct(Collection $comments, Post $post)
    {
        // Provide only top-level comments
        $this->comments = $comments->whereNull('parent_id')->sortByDesc('created_at');
        $this->post = $post;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // dd('Rendering CommentSection');
        return view('components.comment-section');
    }
}
