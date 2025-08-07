@extends('templates.base')
@section('title', 'Product Detail')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2 mt-2 ">
            <x-navbar />
            <div class="flex flex-wrap gap-2 justify-around mx-auto">
                <div>
                    <img class="w-full max-w-xl rounded-2xl" src="{{asset('storage/'.$product->image)}}" alt="">
                </div>
                <div class="flex-auto flex flex-col">
                    <div class="flex-auto">
                        <h1 class="text-2xl font-semibold">{{$product->name}}</h1>
                        <hr class="w-1/2">

                        <div class="flex justify-between items-center lg:max-w-1/2">
                            <p class="text-xl mt-2">Rp. {{$product->price}}</p>
                            <p class="text-md">Stock: {{ $product->quantity }}</p>

                        </div>
                        <p class="text-md">SKU: {{ $product->sku }}</p>
                        <p class="py-4 pb-2 text-md ">{{$product->description}}</p>

                    </div>
                    <div class="flex gap-2">
                        <a href="{{route('products.edit', $product->id)}}"><i
                                class="bi bi-pencil text-2xl hover:text-success"></i></a>
                        <form action="{{route('products.destroy', $product->id)}}" method="post">
                            @csrf
                            @method('delete')
                            <button onclick="return confirm('Are you sure?')" type="submit"><i
                                    class="bi bi-trash text-2xl hover:text-error"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection