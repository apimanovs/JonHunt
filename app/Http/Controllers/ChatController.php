<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with('messages')->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();
    
        return response()->json($chats);
    }


    public function show()
    {
        $chats = Chat::with('messages')->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        return view('chat.show', compact('chats'));
    }

    public function openChat(User $user)
{
    $currentUser = auth()->user();

    $chat = Chat::findOrCreateChat($currentUser->id, $user->id);

    return view('chat.show', [
        'chat' => $chat,
        'messages' => $chat->messages()->with('sender')->get(),
        'recipient' => $user,
    ]);
}


}
