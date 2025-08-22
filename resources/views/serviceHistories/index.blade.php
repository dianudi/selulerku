@extends('templates.base')
@section('title', 'Service Histories')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <div class="stats shadow w-full my-4">
                <div class="stat">
                    <div class="stat-figure hidden md:block text-secondary">
                        <i class="bi bi-clock-history text-3xl"></i>
                    </div>
                    <div class="stat-title">Pending Services</div>
                    <div class="stat-value">{{ $pendingCount }}</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary hidden md:block">
                        <i class="bi bi-gear text-3xl"></i>
                    </div>
                    <div class="stat-title">On Process Services</div>
                    <div class="stat-value">{{ $onProcessCount }}</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary hidden md:block">
                        <i class="bi bi-check-circle text-3xl"></i>
                    </div>
                    <div class="stat-title">Done Services</div>
                    <div class="stat-value">{{ $doneCount }}</div>
                </div>
            </div>
            <div class="flex justify-between items-center my-4">
                <h1 class="text-2xl font-bold">Service Histories</h1>
                <a href="{{ route('servicehistories.create') }}" class="btn btn-primary">Create New</a>
            </div>

            @if (session()->has('success'))
            <div role="alert" class="alert alert-success max-w-sm ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="dark:text-white">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Table for Desktop --}}
            <div class="overflow-x-auto hidden md:block">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceHistories as $serviceHistory)
                        <tr>
                            <th>{{ $serviceHistory->id }}</th>
                            <td>{{ $serviceHistory->invoice_number }}</td>
                            <td>{{ $serviceHistory->customer->name }}</td>
                            <td><span class="badge @switch($serviceHistory->status)
                                        @case('pending')
                                            badge-warning
                                            @break
                                        @case('on_process')
                                            badge-info
                                            @break
                                        @case('done')
                                            badge-success
                                            @break
                                    @endswitch">{{ $serviceHistory->status }}</span>
                            </td>
                            <td>Rp. {{ number_format($serviceHistory->details->sum('price'), 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('servicehistories.show', $serviceHistory) }}"
                                    class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('servicehistories.edit', $serviceHistory) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No service history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- List for Mobile --}}
            <div class="block md:hidden">
                @forelse ($serviceHistories as $serviceHistory)
                <div class="card bg-base-100 shadow-xl mb-4">
                    <div class="card-body">
                        <h2 class="card-title">{{ $serviceHistory->invoice_number }}</h2>
                        <p><strong>Customer:</strong> {{ $serviceHistory->customer->name }}</p>
                        <p><strong>Status:</strong> <span class="badge @switch($serviceHistory->status)
                                        @case('pending')
                                            badge-warning
                                            @break
                                        @case('on_process')
                                            badge-info
                                            @break
                                        @case('done')
                                            badge-success
                                            @break
                                    @endswitch">{{ $serviceHistory->status }}</span>
                        </p>
                        <p><strong>Total Price:</strong> Rp.
                            {{ number_format($serviceHistory->details->sum('price'), 0, ',', '.') }}</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('servicehistories.show', $serviceHistory) }}"
                                class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('servicehistories.edit', $serviceHistory) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center">No service history found.</p>
                @endforelse
            </div>

            <div class="my-4">
                {{ $serviceHistories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection