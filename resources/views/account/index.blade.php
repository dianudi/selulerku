@extends('templates.base')
@section('title', 'Account Setting')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <div class="w-full">
                <h1 class="text-2xl font-bold">Account Setting</h1>
                <div class="mt-5">
                    @if (session('success'))
                    <div role="alert" class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title">Update Account</h2>
                            <form action="{{ route('account.update') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Name</legend>
                                    <input type="text" class="input w-full @error('name') border-red-500 @enderror"
                                        name="name" value="{{ auth()->user()->name }}" placeholder="Type here" />
                                    @error('name')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Email</legend>
                                    <input type="email" class="input w-full @error('email') border-red-500 @enderror"
                                        name="email" value="{{ auth()->user()->email }}" placeholder="Type here" />
                                    @error('email')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                                <div class="card-actions justify-end mt-5">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title">Update Password</h2>
                            <form action="{{ route('account.password') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Current Password</legend>
                                    <input type="password"
                                        class="input w-full @error('current_password') border-red-500 @enderror"
                                        name="current_password" placeholder="Type here" />
                                    @error('current_password')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">New Password</legend>
                                    <input type="password"
                                        class="input w-full @error('password') border-red-500 @enderror" name="password"
                                        placeholder="Type here" />
                                    @error('password')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Confirm Password</legend>
                                    <input type="password"
                                        class="input w-full @error('password_confirmation') border-red-500 @enderror"
                                        name="password_confirmation" placeholder="Type here" />
                                    @error('password_confirmation')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </fieldset>
                                <div class="card-actions justify-end mt-5">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection