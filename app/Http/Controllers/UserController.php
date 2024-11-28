<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function convertUser(Request $request, int $userID)
    {
        $user = User::find($userID);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        ($user->is_admin == 1) ? $user->is_admin = 2 : $user->is_admin = 1;
        $user->save();

        return redirect()->back()->with('success', 'User converted successfully');
    }
}
