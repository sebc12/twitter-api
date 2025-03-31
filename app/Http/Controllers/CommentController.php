<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function index()
    {
        return Comment::with('likes')->latest()->get();
    }

    public function store(Request $request, $postId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $comment = Comment::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'content' => $validated['content'],
        ]);

        return response()->json($comment, 201);
    }
}
