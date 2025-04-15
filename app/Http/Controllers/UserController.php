<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function edit()
    {
        return view('profile'); // đã có sẵn file profile.blade.php của bạn
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        return redirect()->route('profile')->with('status', 'Avatar updated successfully!');
    }
}
