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
        $users = User::latest()->where('id', '!=', auth()->id())->get();

        return response()->json($users);
    }

    public function index()
    {
        return view('chat');
    }

    public function getMessages($user_id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($user_id);

        $messages = Message::with('user')
            ->where(function ($q) use ($user_id) {
                $q->where('from', auth()->id())
                    ->where('to', $user_id);
            })->orWhere(function ($q) use ($user_id) {
                $q->where('to', auth()->id())
                    ->where('from', $user_id);
            })->get();

        return response()->json(['user' => $user, 'messages' => $messages]);
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
