<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = User::latest()->where('id', '!=', auth()->id())->get();

        return response()->json($users);
    }
}
