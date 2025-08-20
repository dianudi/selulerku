@extends('templates.base')
@section('title', 'Order Detail')

@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-4 py-2">
            <x-navbar />

            {{-- Header --}}
            <div class="flex items-center mb-4">
                <a href="{{ route('orders.index') }}" class="btn btn-circle btn-ghost mr-2">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold">Order #{{ $order->invoice_number }}</h1>
                    <p class="text-sm opacity-60">Order detail and detail customer</p>
                </div>
            </div>

            @if (session()->has('error'))
            <div role="alert" class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{session('error')}}</span>
            </div>
            @endif

            {{-- Order Details Card --}}
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h3 class="font-semibold opacity-60 mb-1">Customer</h3>
                            <p>{{ $order->customer->name }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold opacity-60 mb-1">Order Date</h3>
                            <p>{{ $order->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold opacity-60 mb-1">Status</h3>
                            <div
                                class="badge @if($order->status == 'paid') badge-success @elseif($order->status == 'unpaid') badge-error @else badge-warning @endif uppercase font-semibold">
                                {{ $order->status }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold opacity-60 mb-1">Cashier</h3>
                            <p>{{ $order->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product List --}}
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title mb-2">Order Item</h2>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->details as $detail)
                                <tr>
                                    <td>
                                        <div class="flex items-center space-x-3">
                                            <div class="avatar">
                                                <div class="mask mask-squircle w-12 h-12">
                                                    <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                        alt="{{ $detail->product->name }}" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $detail->product->name }}</div>
                                                <div class="text-sm opacity-50">{{ $detail->product->category->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-right">Rp. {{ number_format($detail->immutable_price /
                                        $detail->quantity, 0, ',', '.')
                                        }}</td>
                                    <td class="text-right">Rp. {{ number_format($detail->immutable_price, 0, ',', '.')
                                        }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right text-lg">Total</th>
                                    <th class="text-right text-lg">Rp. {{
                                        number_format($order->details->sum('immutable_price'), 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end mt-4 gap-2">
                <a href="#" class="btn btn-outline">
                    <i class="bi bi-printer"></i>
                    Print Invoice
                </a>
                @if ($order->status != 'canceled')
                <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i>
                    Edit Order
                </a>
                @endif

                <form action="{{ route('orders.destroy', $order) }}" method="POST"
                    onsubmit="return confirm('Anda yakin ingin menghapus pesanan ini? Stok akan dikembalikan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection