<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

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


    
    public function store(Request $request,Post $post, Comment $comment)
{
    
    // return $request->user()->id;
    // Gate::authorize('modify', $comment);
    $request->validate([
        'content' => 'required|string',
    ]);

    return Comment::create([
        'content' => $request->content,
        'user_id' => $request->user()->id,
        'post_id' => $post->id
    ]);

}

public function destroy(Post $post, Comment $comment)
    {
        // Authorize the user to delete the comment using the Gate
        Gate::authorize('modify', $comment);

        // If authorization passes, delete the comment
        $comment->delete();

        return ['message' => "The comment ($comment->id) for post ($post->id) has been deleted"];
        // return response()->json(['message' => 'Comment deleted successfully']);
    }

   

}