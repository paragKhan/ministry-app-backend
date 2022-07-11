<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(){
        $conversations =  Conversation::with('user')->get();

        return response()->json($conversations);
    }

    public function show(Conversation $conversation){
        return response()->json($conversation);
    }
}
