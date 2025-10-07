<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class ChatController extends Controller
{
    public function index()
    {
        session()->forget('chat_session');
        return view('chat');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $userMessage = $request->input('message');

        $chat = session('chat_session');

        if (!$chat) {
            $chat = Gemini::chat('gemini-2.5-pro');
        }

        $response = $chat->sendMessage($userMessage);

        session(['chat_session' => $chat]);

        return response()->json([
            'reply' => $response->text(),
        ]);
    }
}

