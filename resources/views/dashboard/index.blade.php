@extends('templates.base')
@section('title', 'Dashboard')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />

        </div>
    </div>
</div>
@endsection