@extends('templates.base')
@section('title', 'Order Management')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            {{-- stat start --}}
            <div class="stats shadow w-full mb-4">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-cash-coin text-4xl"></i>
                    </div>
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-value">Rp. {{number_format($totalRevenue, 0, ',', '.')}}</div>
                    <div class="stat-desc">From orders has been paid</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-box-seam text-4xl"></i>
                    </div>
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                    <div class="stat-desc">From all orders</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-success">
                        <i class="bi bi-check-circle-fill text-4xl"></i>
                    </div>
                    <div class="stat-title">Paid Order</div>
                    <div class="stat-value">{{ $paidOrders }}</div>
                    <div class="stat-desc text-success">Status "paid"</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-error">
                        <i class="bi bi-hourglass-split text-4xl"></i>
                    </div>
                    <div class="stat-title">Unpaid Order</div>
                    <div class="stat-value">{{ $unpaidOrders }}</div>
                    <div class="stat-desc text-error">Status "unpaid"</div>
                </div>
            </div>
            {{-- stat end --}}
            {{-- search product start --}}
            <form action="{{route('orders.index')}}" method="get">
                <div class="flex flex-wrap-reverse md:flex-nowrap items-center gap-2">
                    <div class="flex w-full items-center justify-between">
                        <h1 class="text-xl md:text-2xl font-bold flex-auto">Orders</h1>
                        <a href="{{route('products.index')}}" class="btn btn-outline border-0"><i
                                class="bi bi-plus text-2xl"></i></a>

                    </div>
                    <label class="input w-full lg:max-w-md">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input name="search" type="search" class="grow" value="{{request()->search}}"
                            placeholder="Search" />
                        <kbd class="kbd kbd-sm">⌘</kbd>
                        <kbd class="kbd kbd-sm">K</kbd>
                    </label>
                </div>
            </form>
            {{-- search product end --}}

            {{-- order list start --}}
            <ul class="list bg-base-100 rounded-box shadow-md">
                @forelse ($orders as $order)
                <li class="list-row">
                    <div><img class="size-10 rounded-box"
                            src="{{$order->details[0]->product->image ? asset('storage/'.$order->details[0]->product->image) : 'https://img.icons8.com/liquid-glass/200/no-image.png'}}"
                            </div>
                        <div>
                            <div class="text-xs uppercase font-semibold opacity-60">{{$order->invoice_number}}</div>
                            <div> <a
                                    href="{{ route('orders.show', ['order' => $order]) }}">{{$order->details[0]->product->name}}</a>
                            </div>
                            <div class="text-xs font-semibold opacity-60">{{$order->customer->name}}</div>
                            <div class="text-xs text-green-500 font-semibold">Rp.
                                {{number_format($order->details->sum('immutable_price'), 0, ',', '.')}} <span
                                    class="uppercase font-bold @if($order->status == 'paid') text-success @elseif($order->status == 'unpaid') text-error @else text-warning @endif">({{$order->status}})</span>
                            </div>
                            <div class="text-xs font-semibold opacity-60">Cashier {{$order->user->name}}</div>

                        </div>

                </li>
                @empty
                <div class="text-center text-lg mt-2">No orders found</div>
                @endforelse
            </ul>
            {{-- order list end --}}
            {{$orders->links()}}
        </div>
    </div>
</div>
@endsection