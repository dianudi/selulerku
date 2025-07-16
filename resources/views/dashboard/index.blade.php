@extends('templates.base')
@section('title', 'Dashboard')
@section('content')
<div class="w-full">
    <x-navbar />
    <div class="flex">
        <x-sidebar />
        <div class="flex-[1]"></div>
    </div>
</div>
@endsection