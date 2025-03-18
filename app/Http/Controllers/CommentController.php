<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    //
    public function index(Post $post)
    {
        return $post->comments;
    }

    
    public function store(Request $request,Post $post)
{
    
    // return $request->user()->id;
    $request->validate([
        'content' => 'required|string',
    ]);

    return Comment::create([
        'content' => $request->content,
        'user_id' => $request->user()->id,
        'post_id' => $post->id
    ]);

}
}