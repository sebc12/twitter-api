<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;

class LikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        $validated = $request->validate([
            'likeable_id' => 'required|integer',
            'likeable_type' => 'required|string|in:post,comment',
        ]);

        $user = auth()->user();  // Henter den autentificerede bruger

        $modelClass = $validated['likeable_type'] === 'post' ? Post::class : Comment::class;
        $likeable = $modelClass::findOrFail($validated['likeable_id']);

        $like = $likeable->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete(); // Hvis brugeren allerede har liket, fjerner vi like
            return response()->json(['message' => 'Like removed'], 200);
        } else {
            $likeable->likes()->create(['user_id' => $user->id]);
            return response()->json(['message' => 'Liked'], 201);
        }
    }
}
