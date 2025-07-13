@extends('templates.base')
@section('title', 'Update Password')
@section('content')
<div class="min-h-screen mx-auto flex items-center">
    <div class="w-full lg:max-w-md mx-auto p-4 dark:border dark:border-[#3E3E3A] dark:bg-slate-800 rounded-2xl">
        <h1 class="text-center font-bold text-3xl mb-3">Update Password</h1>
        @error('email')
        <div role="alert" class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="dark:text-white">{{$message}}</span>
        </div>
        @enderror
        <form action="{{route('password.update')}}" method="post">
            @csrf
            <input type="hidden" name="email" value="{{request()->email}}">
            <input type="hidden" value="{{$token}}" name="token">
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Password</legend>
                <input type="password" class="input w-full @error('password') border-red-500 @enderror" name="password"
                    placeholder="Password" />
                @error('password')
                <div class="text-red-500">{{$message}}</div>
                @enderror
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Password Confirmation</legend>
                <input type="password" class="input w-full @error('password_confirmation') border-red-500 @enderror"
                    name="password_confirmation" placeholder="Password" />
                @error('password_confirmation')
                <div class="text-red-500">{{$message}}</div>
                @enderror
            </fieldset>
            <button class="btn btn-primary w-full my-3">Update Password</button>
        </form>
    </div>
</div>
@endsection