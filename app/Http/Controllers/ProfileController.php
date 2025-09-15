<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        $filename = time() . '.' . $request->file('avatar')->getClientOriginalExtension();

        $path = $request->file('avatar')->storeAs('uploads/avatar', $filename, 'public');

        $user->avatar = $path;
        $user->save();

        return back()->with('success', 'Avatar updated!');
    }

}

