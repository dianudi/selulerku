@extends('templates.base')
@section('title', 'Product Management')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            {{-- stats start --}}
            <div class="stats shadow w-full">
                <div class="stat place-items-center">
                    <div class="stat-title">Total Products</div>
                    <div class="stat-value">{{$productCount}}</div>
                    {{-- <div class="stat-desc"></div> --}}
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Sold Today</div>
                    <div class="stat-value">{{$productSoldCountToday}}</div>
                    @php
                    $soldDiff = $productSoldCountToday - $productSoldCountYesterday;
                    if ($productSoldCountYesterday > 0) {
                    $soldPercentage = round(($soldDiff / $productSoldCountYesterday) * 100);
                    } else if ($productSoldCountToday > 0) {
                    $soldPercentage = 100;
                    } else {
                    $soldPercentage = 0;
                    }
                    @endphp
                    <div class="stat-desc text-secondary">
                        {{ $soldDiff >= 0 ? '+' : '' }}{{ $soldDiff }}
                        ({{ $soldPercentage >= 0 ? '+' : '' }}{{ $soldPercentage }}%)
                    </div>
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Profit Today</div>
                    <div class="stat-value">Rp. {{number_format($productProfitToday, 0, ',', '.')}}</div>
                    @php
                    $profitDiff = $productProfitToday - $productProfitYesterday;
                    if ($productProfitYesterday > 0) {
                    $profitPercentage = round(($profitDiff / $productProfitYesterday) * 100);
                    } else if ($productProfitToday > 0) {
                    $profitPercentage = 100;
                    } else {
                    $profitPercentage = 0;
                    }
                    @endphp
                    <div class="stat-desc">
                        {{ $profitDiff >= 0 ? '+' : '' }}{{ number_format($profitDiff, 0, ',', '.') }}
                        ({{ $profitPercentage >= 0 ? '+' : '' }}{{ $profitPercentage }}%)
                    </div>
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Low Stock Items</div>
                    <div class="stat-value text-error">{{$lowStockCount}}</div>
                    <div class="stat-desc">Items with quantity &lt; 5</div>
                </div>
            </div>
            {{-- stats end --}}

            {{-- search product start --}}
            <form action="{{route('products.index')}}" method="get">
                <div class="flex flex-wrap-reverse md:flex-nowrap items-center gap-2">
                    <div class="flex w-full items-center justify-between">
                        {{-- <h1 class="text-xl md:text-2xl font-bold flex-auto">Products</h1> --}}
                        @if(auth()->user()->role != 'cashier')
                        <a href="{{route('products.create')}}" class="btn btn-outline border-0"><i
                                class="bi bi-plus text-2xl"></i></a>
                        @endif
                        <button id="cart-button" type="button" class="btn btn-outline border-0 ms-auto lg:hidden"><i
                                class="bi bi-cart text-2xl"></i></button>
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

            {{-- product list card start --}}
            <div class="flex gap-1 mt-5 ">
                <div class="flex-auto">
                    <h2 class="text-lg font-bold">Product List</h2>
                    <div class="flex flex-wrap gap-1 justify-between lg:justify-start">
                        @forelse ($products as $product)
                        <div class="card bg-base-100 w-56 shadow-sm">
                            <figure>
                                <img class="h-48 object-cover"
                                    src="{{$product->image ? asset('storage/'.$product->image) : 'https://img.icons8.com/liquid-glass/200/no-image.png'}}"
                                    alt="{{$product->name}}" />
                            </figure>
                            <div class="card-body p-3 flex flex-col">
                                <div class="flex-grow">
                                    <div class="flex justify-between items-start">
                                        <div class="badge badge-outline">{{$product->category->name}}</div>
                                        @if ($product->created_at->diffInDays() < 7) <div
                                            class="badge badge-secondary badge-outline text-xs">New
                                    </div>
                                    @endif
                                </div>
                                <h2 class="card-title text-base mt-2">
                                    <a class="hover:underline"
                                        href="{{route('products.edit', $product->id)}}">{{$product->name}}</a>
                                </h2>
                                <p class="text-lg font-bold mt-1">Rp. {{ number_format($product->sell_price, 0, ',',
                                    '.') }}
                                </p>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <div class="text-sm">
                                    <span class="font-semibold {{ $product->quantity < 5 ? 'text-error' : '' }}">Stock:
                                        {{$product->quantity}}</span>
                                    <span class="mx-1">|</span>
                                    <span>Sold: {{$product->orderDetails()->sum('quantity')}}</span>
                                </div>
                                <button type="button" data-id="{{$product->id}}" data-product-name="{{$product->name}}"
                                    data-product-price="{{$product->sell_price}}"
                                    data-product-image="{{$product->image ? asset('storage/'.$product->image) : 'https://img.icons8.com/liquid-glass/200/no-image.png'}}"
                                    class="add-to-cart btn btn-sm btn-primary active:scale-90 transition-all">
                                    <i class="bi bi-cart-plus text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center w-full text-lg">No products found</div>
                    @endforelse
                </div>
            </div>
            {{-- Cart start --}}
            <div id="cart-sidebar-container" class="hidden lg:block min-w-[400px] px-2 border-l border-slate-700">
                @if (session()->has('error'))
                <div role="alert" class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{session('error')}}.</span>
                </div>
                @endif
                <div id="cart">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold">Carts</h2>
                        <button type="button" id="reset-cart-button" class="btn btn-sm btn-neutral">Reset</button>
                    </div>
                    <form id="orderForm" action="{{route('orders.store')}}" method="POST">
                        @csrf
                        <ul id="cart-items" class="list bg-base-100 rounded-box shadow-md">
                            <!-- Cart items will be injected here by JavaScript -->
                        </ul>
                        <div id="empty-cart-message" class="text-center py-4 hidden">
                            <p>Your cart is empty.</p>
                        </div>

                        <div class="p-2 rounded-md">
                            <div class="flex justify-between items-center font-bold">
                                <span>Subtotal</span>
                                <span id="cart-subtotal">Rp. 0</span>
                            </div>
                            <div class="flex justify-between items-center font-bold mt-2">
                                <span>Total</span>
                                <span id="cart-total">Rp. 0</span>
                            </div>
                        </div>

                        <div class=" p-2 rounded-md  flex items-center justify-between">
                            <span id="selected-customer-name" class="text-gray-500">No customer selected</span>
                            <button type="button" onclick="customer_selection_modal.showModal()"
                                class="btn btn-sm">Select
                                Customer</button>
                        </div>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Status</legend>
                            <select name="status" class="select w-full">
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </fieldset>
                        <input type="hidden" name="customer_id" id="customer_id_hidden">
                        <button id="checkout" type="submit"
                            class="btn btn-primary w-full mx-auto block md:max-w-xs my-3">Checkout</button>
                    </form>
                </div>
            </div>
            {{-- Cart end --}}
        </div>

    </div>
</div>
</div>
<x-customer-selection-modal />

<dialog id="cart_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <!-- Cart content will be moved here by JS on mobile -->
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@endsection