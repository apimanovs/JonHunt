<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id'   => 'required|integer|exists:orders,id',
            'Content'    => 'required|string',
            'ReceiverID' => 'required|integer|exists:users,id',
        ]);
    
        $data['SenderID'] = Auth::id();
    
        $message = Message::create($data);
        
        logger('Broadcasting message: ' . $message->Content);
        broadcast(new MessageSent($message))->toOthers();
        
    
        return response()->json($message, 201);
    }
    
}
