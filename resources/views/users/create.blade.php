@extends('templates.base')
@section('title', 'Create New User')
@section('content')
<div class="w-full">
    <x-navbar />
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <div class="w-full">
                <h1 class="text-lg md:text-2xl font-bold text-center">Create New User</h1>
                <form action="{{route('users.store')}}" class="mx-auto block max-w-lg" method="post">
                    @csrf
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Name</legend>
                        <input name="name" type="text" value="{{old('name')}}"
                            class="input w-full @error('name') border-red-500 @enderror" placeholder="Type here" />
                        @error('name')
                        <div class="text-red-500">{{$message}}</div>
                        @enderror
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Email</legend>
                        <input name="email" type="email" value="{{old('email')}}"
                            class="input w-full @error('email') border-red-500 @enderror" placeholder="Type here" />
                        @error('email')
                        <div class="text-red-500">{{$message}}</div>
                        @enderror
                    </fieldset>
                    <div class="my-1 text-sm font-bold">Role</div>
                    <select class="select w-full  @error('role') border-red-500 @enderror" name="role">
                        <option selected value="admin">Admin</option>
                    </select>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Password</legend>
                        <input name="password" type="password"
                            class="input w-full @error('password') border-red-500 @enderror" placeholder="Type here" />
                        @error('password')
                        <div class="text-red-500">{{$message}}</div>
                        @enderror
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Password Confirmation</legend>
                        <input name="password_confirmation" type="password"
                            class="input w-full @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Type here" />
                        @error('password_confirmation')
                        <div class="text-red-500">{{$message}}</div>
                        @enderror
                    </fieldset>
                    <button class="btn btn-primary w-full my-3">Create User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection