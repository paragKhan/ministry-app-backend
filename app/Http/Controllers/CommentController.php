<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function postComment(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
            'blog_id' => 'required|exists:blogs,id'
        ]);

        $comment = Comment::create([
            'text' => $request->text,
            'blog_id' => $request->blog_id,
            'user_id' => auth()->id()
        ]);

        return response()->json($comment);
    }

    public function deleteComment(Comment $comment){
        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}