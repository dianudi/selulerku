<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingController extends Controller
{
    public function index()
    {
        return view('account.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        $user = User::find(Auth::user()->id);
        $user->update($validated);
        return redirect()->route('account.index')->with('success', 'Account updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password:web',
            'password' => 'required',
            'password_confirmation' => 'required|confirmed:password',
        ]);
        $user = User::find(Auth::user()->id);
        $user->update(['password' => Hash::make($validated['password'])]);
        return redirect()->route('account.index')->with('success', 'Password updated successfully');
    }
}
