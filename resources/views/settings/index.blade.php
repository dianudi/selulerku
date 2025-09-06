@extends('templates.base')
@section('title', 'Settings')

@section('content')
    <div class="w-full">
        <div class="flex relative">
            <x-sidebar />
            <div class="w-full px-4 py-2">
                <x-navbar />

                {{-- Header --}}
                <div class="flex items-center mb-4">
                    <h1 class="text-2xl font-bold">Settings</h1>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            <fieldset class="fieldset mb-4">
                                <legend class="fieldset-legend">Receipt Address</legend>
                                <textarea name="receipt_address" class="textarea textarea-bordered h-24 w-full">{{ $settings['receipt_address'] ?? '' }}</textarea>
                            </fieldset>
                            <fieldset class="fieldset mb-4">
                                <legend class="fieldset-legend">Receipt Footer</legend>
                                <input type="text" name="receipt_footer" class="input input-bordered w-full" value="{{ $settings['receipt_footer'] ?? '' }}">
                            </fieldset>
                            <div class="flex justify-end">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection