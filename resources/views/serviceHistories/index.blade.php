@extends('templates.base')
@section('title', 'Service Histories')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <div class="stats shadow w-full ">
                <div class="stat">
                    <div class="stat-figure hidden md:block text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-8 w-8 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-title">Total</div>
                    <div class="stat-value">31K</div>
                    {{-- <div class="stat-desc">Jan 1st - Feb 1st</div> --}}
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-8 w-8 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <div class="stat-title">Profit Today</div>
                    <div class="stat-value">Rp. 4,200</div>
                    <div class="stat-desc">↗︎ 400 (22%)</div>
                </div>

                {{-- <div class="stat">
                    <div class="stat-figure text-secondary hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-8 w-8 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                            </path>
                        </svg>
                    </div>
                    <div class="stat-title"></div>
                    <div class="stat-value">1,200</div>
                    <div class="stat-desc">↘︎ 90 (14%)</div>
                </div> --}}
            </div>
            <div class="flex justify-end">

                <div class="flex items-center">
                    <a href="{{route('servicehistories.create')}}" class="btn btn-outline btn-accent border-0"><i
                            class="bi bi-plus text-2xl"></i></a>
                </div>
            </div>
            @if (session()->has('success'))
            <div role="alert" class="alert alert-success max-w-sm ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="dark:text-white">{{session('success')}}</span>
            </div>

            @endif

            {{-- list start --}}
            <ul class="list bg-base-100 rounded-box shadow-md">
                @forelse ($serviceHistories as $serviceHistory)
                <li class="list-row">
                    <div class="flex items-center"><i class="bi bi-gear-fill text-2xl "></i>
                    </div>
                    <div>
                        <div><a
                                href="{{route('servicehistories.show', $serviceHistory->id)}}">{{$serviceHistory->details()->first()?->kind}}</a>
                        </div>
                        <div class="text-xs uppercase font-semibold opacity-60">
                            {{$serviceHistory->created_at->format('d-m-Y')}}/{{$serviceHistory->invoice_number}}/{{$serviceHistory->id}}
                        </div>
                    </div>
                </li>

                @empty
                <li class="text-center text-lg">No service history</li>
                @endforelse

            </ul>
            {{-- list end --}}
        </div>
    </div>
</div>
@endsection