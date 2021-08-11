<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getUserList(): \Illuminate\Http\JsonResponse
    {
        $users = User::latest()->get();

        return response()->json($users);
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
