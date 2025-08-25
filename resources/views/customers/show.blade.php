@extends('templates.base')
@section('title', 'Customer Detail')
@section('content')
    <div class="w-full">
        <div class="flex relative">
            <x-sidebar />
            <div class="w-full p-4">
                <x-navbar />
                <div class="flex justify-between flex-wrap lg:flex-nowrap px-2 gap-4">
                    <div class="flex justify-between items-center w-full lg:max-w-md">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $customer->name }}</h1>
                            <p class="text-xs opacity-60">Customer since {{ $customer->created_at->diffForHumans() }}</p>
                            <a href="tel:{{ $customer->phone_number }}"
                                class="text-blue-400 hover:underline">{{ $customer->phone_number }}</a>
                            <p>{{ $customer->address }}</p>

                        </div>
                        <div class="ml-4">
                            <a href="{{ route('customers.edit', $customer->id) }}"><i
                                    class="bi bi-pencil text-2xl hover:text-success"></i></a>
                        </div>
                    </div>
                    {{-- stats start --}}
                    <div class="stats shadow w-full">
                        <div class="stat place-items-center">
                            <div class="stat-title">Total Order</div>
                            <div class="stat-value">Rp. {{ number_format($totalOrder, 0, ',', '.') }}</div>
                        </div>

                        <div class="stat place-items-center">
                            <div class="stat-title">Total Service</div>
                            <div class="stat-value">Rp. {{ number_format($totalService, 0, ',', '.') }}</div>
                        </div>

                        <div class="stat place-items-center">
                            <div class="stat-title">Total Revenue</div>
                            <div class="stat-value text-secondary">Rp.
                                {{ number_format($totalOrder + $totalService, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    {{-- stats end --}}
                </div>

                <div class="flex flex-wrap md:flex-nowrap gap-4 mt-4">
                    <div class="w-full">
                        <div class="bg-base-100 rounded-box shadow-md">
                            <h2 class="p-4 text-lg font-semibold">Latest Order History</h2>
                            <ul class="list">
                                @forelse ($customer->orders->sortByDesc('created_at') as $order)
                                    <a href="{{ route('orders.show', ['order' => $order]) }}">
                                        <li class="list-row">
                                            <div class="flex-shrink-0">
                                                <img class="size-12 rounded-box"
                                                    src="{{ $order->details->first()->product->image ? asset('storage/' . $order->details->first()->product->image) : 'https://img.daisyui.com/images/stock/photo-1559703248-dcaaec9fab78.jpg' }}" />
                                            </div>
                                            <div class="flex-grow">
                                                <div class="font-semibold">{{ $order->details->count() }} item(s)</div>
                                                <div class="text-xs opacity-60">
                                                    {{ $order->details->pluck('product.name')->join(', ') }}
                                                </div>
                                                <div class="text-xs uppercase font-semibold opacity-60">Total Rp.
                                                    {{ number_format($order->details->sum('immutable_price'), 0, ',', '.') }}
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end">
                                                @php
                                                    $statusColor = [
                                                        'paid' => 'badge-success',
                                                        'unpaid' => 'badge-warning',
                                                        'canceled' => 'badge-error',
                                                    ][$order->status];
                                                @endphp
                                                <div class="badge {{ $statusColor }}">{{ $order->status }}
                                                </div>
                                                <div class="text-xs font-semibold opacity-60 mt-1">
                                                    {{ $order->created_at->format('d-m-Y') }}</div>
                                            </div>
                                        </li>
                                    </a>
                                @empty
                                    <li class="text-center p-4">No orders found</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="bg-base-100 rounded-box shadow-md">
                            <h2 class="p-4 text-lg font-semibold">Latest Service History</h2>
                            <ul class="list">
                                @forelse ($customer->serviceHistories->sortByDesc('created_at') as $service)
                                    <a
                                        href="{{ route('servicehistories.show', ['serviceHistory' => $service]) }}">
                                        <li class="list-row">
                                            <div class="flex-grow">
                                                <div class="font-semibold">{{ $service->details->first()->kind }}</div>
                                                <div class="text-xs opacity-60">
                                                    {{ $service->details->pluck('description')->join(', ') }}</div>
                                                <div class="text-xs uppercase font-semibold opacity-60">Total Rp.
                                                    {{ number_format($service->details->sum('price'), 0, ',', '.') }}
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs font-semibold opacity-60">
                                                    {{ $service->created_at->format('d-m-Y') }}</div>
                                            </div>
                                        </li>
                                    </a>
                                @empty
                                    <li class="text-center p-4">No services found</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection