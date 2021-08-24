<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function changeAdminPasswordForBeforeLogin($admin_id, Request $request): array
    {
        $admin = User::find($admin_id);

        $admin->update(['password' => Hash::make($request->input('password'))]);

        return (['admin' => $admin]);
    }
}
