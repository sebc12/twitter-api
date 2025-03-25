<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        return Post::with('user', 'comments', 'likes')->latest()->get();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:280', // Restriction like Twitter
        ]);

        // Attach the authenticated user's ID to the validated data
        $validated['user_id'] = Auth::id();

        // Create the Post directly using the Post model
        $post = Post::create($validated);

        return response()->json($post, 201);
    }
}
