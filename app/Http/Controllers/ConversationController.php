<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function store(Request $request)
    {
        return 'store conversation';
    }

    public function getMessages(): \Illuminate\Http\JsonResponse
    {
        return response()->json([]);
    }
}
