<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        if (Gate::denies('superadmin') && Gate::denies('admin')) return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = $request->has('search') ? User::where('name', 'like', '%' . $request->search . '%')->paginate(15) : User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User($request->validated());
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function activate(User $user)
    {
        if ($user->role == 'superadmin') return redirect()->route('users.index')->with('error', 'Superadmin cannot be deactivated.');
        if ($user->id == Auth::user()->id) return redirect()->route('users.index')->with('error', 'You cannot deactivate your own account.');
        $user->active = !$user->active;
        $user->save();
        return redirect()->route('users.index')->with('success', 'User status changed successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->orders()->count() > 0 || $user->customers()->count() > 0 || $user->products()->count() > 0) {
            return redirect()->route('users.index')->with('error', 'User cannot be deleted because it has associated products, customers or orders.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
