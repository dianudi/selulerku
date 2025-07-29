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
                    <div class="stat-value">31K</div>
                    {{-- <div class="stat-desc">From January 1st to February 1st</div> --}}
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Sold</div>
                    <div class="stat-value">4,200</div>
                    <div class="stat-desc text-secondary">↗︎ 40 (2%)</div>
                </div>

                <div class="stat place-items-center">
                    <div class="stat-title">Profit</div>
                    <div class="stat-value">Rp. 1,200</div>
                    <div class="stat-desc">↘︎ 90 (14%)</div>
                </div>
            </div>
            {{-- stats end --}}

            {{-- search product start --}}
            <form action="{{route('products.index')}}" method="get">
                <div class="flex flex-wrap-reverse md:flex-nowrap items-center gap-2">
                    <div class="flex w-full items-center">
                        <h1 class="text-xl md:text-2xl font-bold flex-auto">Products</h1>
                        <a href="{{route('products.create')}}" class="btn btn-outline border-0"><i
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

            {{-- product list card start --}}
            <div class="flex flex-wrap gap-1 justify-between lg:justify-start mt-5">
                @forelse ($products as $product)
                <div class="card bg-base-100 w-56 shadow-sm">
                    <figure>
                        <img src="{{$product->image ? asset('storage/'.$product->image) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp'}}"
                            alt="{{$product->name}}" />
                    </figure>
                    <div class="card-body p-2">
                        <h2 class="card-title">
                            <a class="hover:underline"
                                href="{{route('products.show', $product->id)}}">{{$product->name}}</a>
                            @if ($product->created_at->diffInDays() < 7) <div
                                class="badge badge-secondary badge-outline text-xs">New
                    </div>

                    @endif
                    </h2>
                    <div>Rp. {{$product->price}}</div>
                    <div>Sold {{$product->orderDetails()->count()}}</div>
                    <div class="card-actions justify-between items-center">
                        <div class="badge badge-outline">{{$product->category->name}}</div>
                        <div>
                            <button id="addToCart" type="button" data-id="{{$product->id}}"
                                class="cursor-pointer active:scale-90 transition-all"><i
                                    class="bi bi-cart text-2xl"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center w-full text-lg">No products found</div>
            @endforelse
            {{-- product list card end --}}
        </div>
    </div>
</div>
</div>
@endsection