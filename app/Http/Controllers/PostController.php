<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;


class PostController extends Controller implements HasMiddleware
{
    public static function middleware() {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {   
    //     $post = Post::all();
    //     return $post;
    // }

    public function index()
{
    // Start with an empty collection of posts
    $posts = collect();

    // Always return public posts
    $publicPosts = Post::where('status', 'public')->get();
    $posts = $posts->merge($publicPosts);

    // If user is authenticated, return their private posts as well
    if (auth()->check()) {
        // Fetch private posts belonging to the authenticated user
        $userPrivatePosts = Post::where('user_id', auth()->id())
                                ->where('status', 'private')
                                ->get();

        $posts = $posts->merge($userPrivatePosts);
    }

    return response()->json($posts);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'status' => 'in:public,private',
        ]);

        // $fields['user_id'] = Auth::id();
        $post = $request->user()->posts()->create($fields);


        // $post = Post::create($fields);


        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Post $post)
    {
        $this->authorize('view', $post);
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);
        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'status' => 'in:public,private',
        ]);

        $post->update($fields);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();
        return ['message' => "The post ($post->id) has been deleted"];
    }

}
