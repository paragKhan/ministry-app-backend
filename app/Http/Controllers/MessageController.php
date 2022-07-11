<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'text' => ['nullable', 'string', Rule::requiredIf(!$request->attachment)],
            'attachment' => 'mimes:jpeg,jpg,png,pdf'
        ]);

        $conversation = $request->user()->conversations()->firstOrCreate();

        $message = $conversation->messages()->create([
            'text' => $request->text,
            'senderable_type' => User::class,
            'senderable_id' => auth()->id()
        ]);

        if($request->has('attachment') && $request->attachment){
            $message->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        return response()->json($message);
    }

    public function inbox(){
        return auth()->user()->conversations()->firstOrCreate()->messages;
    }
}
