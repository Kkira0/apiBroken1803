<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CommentController extends Controller implements HasMiddleware
{
    //
    public static function middleware() {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

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