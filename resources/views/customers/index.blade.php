@extends('templates.base')
@section('title', 'Customer Management')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-4">
            <x-navbar />

            {{-- Header --}}
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold">Customer Management</h1>
                    <p class="text-sm opacity-60">Total {{ $customers->total() }} customers</p>
                </div>

            </div>

            {{-- Alerts --}}
            @if (session()->has('success'))
            <div role="alert" class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="dark:text-white">{{ session('success') }}</span>
            </div>
            @endif
            @if (session()->has('error'))
            <div role="alert" class="alert alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            {{-- Customer Grid --}}
            @if ($customers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($customers as $customer)
                <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="card-body">
                        <h2 class="card-title">
                            <a href="{{ route('customers.show', $customer->id) }}" class="link link-hover">{{
                                $customer->name }}</a>
                            @if ($customer->created_at->isToday())
                            <div class="badge badge-secondary">New</div>
                            @endif
                        </h2>
                        <p class="text-sm opacity-70">{{ $customer->address }}</p>
                        <a href="tel:{{ $customer->phone_number }}" class="text-primary hover:underline text-sm">{{
                            $customer->phone_number }}</a>

                        <div class="flex justify-around my-4 text-center">
                            <div>
                                <div class="font-bold text-lg">{{ $customer->orders->count() }}</div>
                                <div class="text-xs opacity-60">Orders</div>
                            </div>
                            <div>
                                <div class="font-bold text-lg">{{ $customer->serviceHistories->count() }}
                                </div>
                                <div class="text-xs opacity-60">Services</div>
                            </div>
                            <div>
                                <div class="font-bold text-lg">
                                    {{ $customer->orders->last()?->created_at->diffForHumans(null, true) ?? 'N/A' }}
                                </div>
                                <div class="text-xs opacity-60">Last Order</div>
                            </div>
                        </div>

                        <div class="card-actions justify-end">
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-ghost btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')"
                                    class="btn btn-error btn-sm btn-outline">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $customers->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <p class="text-lg">No customers found.</p>
            </div>
            @endif
        </div>
    </div>
</div>


@endsection