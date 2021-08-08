<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessages(Request $request): array
    {
        $message = auth()->user()->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSentEvent($message->load('user')))->toOthers();

        return ['status' => 'success'];
    }
}
