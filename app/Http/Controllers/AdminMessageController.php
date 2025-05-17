<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller; 
use App\Models\AdminMessage;
use Illuminate\Http\Request;
use App\Mail\AdminResponseMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:5|max:2000',
        ]);

        $swears = File::exists(resource_path('swears.txt'))
            ? array_map('trim', file(resource_path('swears.txt')))
            : [];

        $messageText = $request->input('message');

        foreach ($swears as $swear) {
            if (stripos($messageText, $swear) !== false) {
                return back()->withErrors(['message' => 'Your message containt bad words!']);
            }
        }

        AdminMessage::create([
            'user_id' => Auth::id(),
            'message' => $messageText,
        ]);

        return back()->with('success', 'Message sent to administration!');
    }

    public function indexForAdmin()
    {
        $messages = AdminMessage::with('user')->latest()->paginate(20);
        return inertia('Admin/Messages/MessagesIndex', ['messages' => $messages]);
    }

    public function reply(Request $request, AdminMessage $adminMessage)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ]);

        $adminMessage->update(['admin_reply' => $request->input('admin_reply')]);

        Mail::to($adminMessage->user->email)->send(new AdminResponseMail($adminMessage));

        return back()->with('success', 'Answer sent to user.');
    }
}
