<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::with('user')->get();

        return response()->json($conversations);
    }

    public function show(Conversation $conversation)
    {
        return response()->json($conversation->messages);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'text' => ['nullable', 'string', Rule::requiredIf(!$request->attachment)],
            'attachment' => 'mimes:jpeg,jpg,png,pdf'
        ]);

        $message = $conversation->messages()->create([
            'text' => $request->text,
            'senderable_type' => Admin::class,
            'senderable_id' => auth()->id()
        ]);

        if ($request->has('attachment') && $request->attachment) {
            $message->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        return response()->json($message);
    }
}
