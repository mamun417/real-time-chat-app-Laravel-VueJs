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

    public function getMessages($user_id): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($user_id);

        $messages = Message::with('user')
            ->where(function ($q) use ($user_id) {
                $q->where('from', auth()->id())
                    ->where('deleted_by_sender', false)
                    ->where('to', $user_id);
            })->orWhere(function ($q) use ($user_id) {
                $q->where('to', auth()->id())
                    ->where('deleted_by_receiver', false)
                    ->where('from', $user_id);
            })
            ->get();

        return response()->json(['user' => $user, 'messages' => $messages]);
    }

    public function sendMessage(Request $request): array
    {
        $message = auth()->user()->messages()->create([
            'to' => $request->input('user_id'),
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSentEvent($message->load('user')));
//        broadcast(new MessageSentEvent($message->load('user')))->toOthers();

        return ['message' => $message];
    }

    public function removeMessage(Request $request, $message_id): array
    {
        $message = Message::findOrFail($message_id);

        $remove_from_me = $request->input('remove_from_me');
        $remove_from_both = $request->input('remove_from_both'); // delete
        $is_message_sender = $message->from === auth()->id();

        // only owner can delete the message
        if ($remove_from_both && $is_message_sender) {
            $message->delete();
        }

        if ($remove_from_me) {
            $data = $is_message_sender ? ['deleted_by_sender' => true] : ['deleted_by_receiver' => true];

            $message->update($data);

            // check delete from sender and receiver
            if ($message->deleted_by_sender && $message->deleted_by_receiver) {
                $message->delete();
            }
        }

        return ['message' => $message];
    }
}
