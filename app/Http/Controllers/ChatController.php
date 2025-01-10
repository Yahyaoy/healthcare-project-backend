<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function getMessages($receiver_username)
    {
        $receiver = User::where('username', $receiver_username)->firstOrFail();

        $messages = Message::where(function ($query) use($receiver) {
            $query->where('sender_id', auth()->id())
                   ->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($receiver) {
            $query->where('sender_id', $receiver->id)
                 ->where('receiver_id',auth()->id());
        })->get();

        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_username'=> 'required|exists:users,username',
            'message' => 'nullable|string',
            'file' =>'nullable|file|max:2048',
        ]);

        $receiver =User::where('username', $request->receiver_username)->firstOrFail();
        $filePath= $request->file ? $request->file->store('messages') :null;

        $message =Message::create(['sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'file' => $filePath,
        ]);

        return response()->json(['message' =>'Message sent successfully.','data' => $message]);
    }

}
