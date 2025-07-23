@extends('templates.base')
@section('title', 'Update Customer')
@section('content')
<div class="w-full">
    <x-navbar />
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <div class="w-full">
                <h1 class="text-lg md:text-2xl font-bold text-center">Update Customer</h1>
                <form action="{{route('customers.update', ['customer' => $customer->id])}}"
                    class="mx-auto block max-w-lg" method="post">
                    @method('PUT')
                    @csrf
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Name</legend>
                        <input name="name" type="text" value="{{old('name') ? old('name') : $customer->name}}"
                            class="input w-full @error('name') border-red-500 @enderror" placeholder="Type here" />
                        @error('name')
                        <div class="text-red-500">{{$message}}</div>
                        @enderror
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Address</legend>
                        <input name="address" type="text"
                            value="{{old('address') ? old('address') : $customer->address}}"
                            class="input w-full @error('address') border-red-500 @enderror" placeholder="Type here" />
                        @error('address')
                        <div class="text-red-500">{{$message}}</div>
                        @enderror
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Phone Number</legend>
                        <input name="phone_number" type="text" inputmode="numeric"
                            value="{{old('phone_number') ? old('phone_number') : $customer->phone_number}}"
                            class="input w-full @error('phone_number') border-red-500 @enderror"
                            placeholder="Type here" />
                        @error('phone_number')
                        <div class="text-red-500">{{$message}}</div>
                        @enderror
                    </fieldset>
                    <button class="btn btn-primary w-full my-3">Update Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection