@extends('templates.base')
@section('title', 'Customer Detail')
@section('content')
<div class="w-full">
    <x-navbar />
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <div class="flex justify-between flex-wrap lg:flex-nowrap px-2">
                <div class="flex justify-between items-center w-full lg:max-w-md">
                    <div>
                        <h1 class="text-2xl font-bold">{{$customer->name}}</h1>
                        <p class="text-xs opacity-60">Customer since {{$customer->created_at->diffForHumans()}}</p>
                        <a href="tel:{{$customer->phone_number}}"
                            class="text-blue-400 hover:underline">{{$customer->phone_number}}</a>
                        <p>{{$customer->address}}</p>

                    </div>
                    <div class="ml-4">
                        <a href="{{route('customers.edit', $customer->id)}}"><i
                                class="bi bi-pencil text-2xl hover:text-success"></i></a>
                    </div>
                </div>
                <div class="stats shadow w-full">
                    <div class="stat place-items-center">
                        <div class="stat-title">Profit of Order</div>
                        <div class="stat-value">Rp. 20,000</div>
                        {{-- <div class="stat-desc">From January 1st to February 1st</div> --}}
                    </div>

                    <div class="stat place-items-center">
                        <div class="stat-title">Profit of Service</div>
                        <div class="stat-value">Rp. 10,000</div>
                        {{-- <div class="stat-desc text-secondary">↗︎ 40 (2%)</div> --}}
                    </div>

                    <div class="stat place-items-center">
                        <div class="stat-title">Total Profit</div>
                        <div class="stat-value text-secondary">Rp. 30,000</div>
                        {{-- <div class="stat-desc">↘︎ 90 (14%)</div> --}}
                    </div>
                </div>
                {{-- stats end --}}
            </div>
            {{-- stats start --}}
            <div class="flex flex-wrap md:flex-nowrap">
                <div class="w-full">
                    <ul class="list bg-base-100 rounded-box shadow-md">

                        <li class="p-4 pb-2 text-xs opacity-60 tracking-wide">Latest Order History</li>
                        @forelse ($customer->orders as $order)
                        <a href="{{ route('orders.show', ['order' => $order]) }}">
                            <li class="list-row">
                                <div>
                                    {{-- <img class="size-10 rounded-box"
                                        src="https://img.daisyui.com/images/profile/demo/1@94.webp" /> --}}
                                </div>
                                <div>
                                    <div>{{ $order->details()[0]->product->name }}</div>
                                    <div class="text-xs uppercase font-semibold opacity-60">Total Rp. {{
                                        $order->details->sum('immutable_price') }}</div>
                                    <div class="text-xs font-semibold opacity-60">
                                        {{$order->created_at->format('d-m-Y')}}</div>
                                </div>

                            </li>
                        </a>

                        @empty
                        <li class="text-center">No orders found</li>
                        @endforelse


                    </ul>
                </div>
                <div class="w-full">
                    <ul class="list bg-base-100 rounded-box shadow-md">

                        <li class="p-4 pb-2 text-xs opacity-60 tracking-wide">Latest Service History</li>

                        @forelse ($customer->serviceHistories as $service)
                        <a href="{{ route('servicehistories.show', ['serviceHistory' => $service]) }}">
                            <li class="list-row">
                                <div>
                                    {{-- <img class="size-10 rounded-box"
                                        src="https://img.daisyui.com/images/profile/demo/1@94.webp" /> --}}
                                </div>
                                <div>
                                    <div>{{ $service->details()[0]->kind }}</div>
                                    <div class="text-xs uppercase font-semibold opacity-60">Total Rp. {{
                                        $service->details->sum('price') }}</div>
                                    <div class="text-xs font-semibold opacity-60">
                                        {{$service->created_at->format('d-m-Y')}}</div>
                                </div>

                            </li>
                        </a>

                        @empty
                        <li class="text-center">No services found</li>
                        @endforelse

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection