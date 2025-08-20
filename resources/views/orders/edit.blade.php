@extends('templates.base')
@section('title', 'Edit Order')

@section('content')
<div class="w-full" id="order-edit-page" data-order-details='@json($cartData)'>
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <form id="orderForm" action="{{ route('orders.update', $order) }}" method="POST" class="w-full">
                @csrf
                @method('PUT')

                <div class="flex items-center my-4">
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-circle btn-ghost mr-2">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">Edit Order #{{ $order->invoice_number }}</h1>
                        <p class="text-sm opacity-60">Change customer, status, or items in the cart.</p>
                    </div>
                </div>

                <div class="flex flex-wrap lg:flex-nowrap gap-4">

                    {{-- Main Content (Left Side) --}}
                    <div class="w-full lg:flex-grow">
                        {{-- Customer & Status --}}
                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Customer</span>
                                        </label>
                                        <div class="flex items-center justify-between p-2 rounded-md border">
                                            <span id="selected-customer-name">{{ $order->customer->name }}</span>
                                            <button type="button" onclick="customer_selection_modal.showModal()"
                                                class="btn btn-sm">Change</button>
                                        </div>
                                        <input type="hidden" name="customer_id" id="customer_id_hidden"
                                            value="{{ $order->customer_id }}">
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Status</span>
                                        </label>
                                        <select name="status" class="select select-bordered w-full">
                                            <option value="paid" @if ($order->status == 'paid') selected @endif>Paid
                                            </option>
                                            <option value="unpaid" @if ($order->status == 'unpaid') selected
                                                @endif>Unpaid
                                            </option>
                                            <option value="canceled" @if ($order->status == 'canceled') selected @endif>
                                                Canceled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Product Search/List (Optional, can be added if needed) --}}
                        <div class="card bg-base-100 shadow-md">
                            <div class="card-body">
                                <h2 class="card-title">Add More Products</h2>
                                <p>To add new products, please go to the <a href="{{ route('products.index') }}"
                                        class="link link-primary">main products page</a> and they will appear in the
                                    cart.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Cart (Right Side) --}}
                    <div id="cart-sidebar-container" class="w-full lg:min-w-[400px] lg:max-w-[400px] px-2">
                        <div id="cart">
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-lg font-bold">Cart Items</h2>
                                <button type="button" id="reset-cart-button"
                                    class="btn btn-sm btn-neutral">Reset</button>
                            </div>

                            <ul id="cart-items" class="list bg-base-100 rounded-box shadow-md max-h-96 overflow-y-auto">
                                {{-- Cart items are rendered by cart.js --}}
                            </ul>
                            <div id="empty-cart-message" class="text-center py-4 hidden">
                                <p>Cart is empty.</p>
                            </div>

                            <div class="p-2 rounded-md mt-2">
                                <div class="flex justify-between items-center font-bold">
                                    <span>Subtotal</span>
                                    <span id="cart-subtotal">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center font-bold mt-2">
                                    <span>Total</span>
                                    <span id="cart-total">Rp 0</span>
                                </div>
                            </div>

                            <button id="checkout" type="submit" class="btn btn-primary w-full mt-3">Update
                                Order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<x-customer-selection-modal />
@endsection