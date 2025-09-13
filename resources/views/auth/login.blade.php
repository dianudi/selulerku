@extends('templates.base')
@section('title', 'Login')

@section('content')
<div class="min-h-screen mx-auto flex items-center">
    <div class="w-full lg:max-w-md mx-auto p-4 dark:border dark:border-[#3E3E3A] dark:bg-slate-800 rounded-2xl">
        <h1 class="text-center font-bold text-3xl mb-3">Login</h1>
        @if (session()->has('status'))
        <div role="alert" class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="dark:text-white">{{session('status')}}</span>
        </div>
        @endif
        <form action="{{route('auth.auth')}}" method="post">
            @csrf
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Email</legend>
                <input type="email" required class="input w-full @error('email') border-red-500 @enderror "
                    value="{{old('email')}}" name="email" placeholder="Email" />
                @error('email')
                <div class="text-red-500">{{$message}}</div>
                @enderror
            </fieldset>
            <fieldset class="fieldset">
                <div class="flex justify-between items-center">
                    <legend class="fieldset-legend">Password</legend>
                    <a class="text-blue-500 hover:text-blue-300" href="{{route('password.request')}}">Forgot
                        Password</a>
                </div>
                <input type="password" required class="input w-full @error('password') border-red-500 @enderror"
                    name="password" placeholder="Password" />
                @error('password')
                <div class="text-red-500">{{$message}}</div>
                @enderror
            </fieldset>
            <fieldset class="fieldset">
                <label class="label">
                    <input type="checkbox" name="remember" class="checkbox" />
                    Remember me
                </label>
            </fieldset>
            <button class="btn btn-primary w-full my-3">Login</button>
        </form>
    </div>
</div>

@endsection