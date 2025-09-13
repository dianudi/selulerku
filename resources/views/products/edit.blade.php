@extends('templates.base')
@section('title', 'Update Product')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />
            <div class="w-full px-2">
                <h1 class="text-lg md:text-2xl font-bold text-center">Update Product</h1>
                <form id="productForm" action="{{route('products.update', ['product' => $product->id])}}"
                    class="mx-auto block max-w-lg" method="post">
                    @csrf
                    @method('PUT')
                    <fieldset class="fieldset">
                        <div class="flex justify-between">
                            <legend class="fieldset-legend">Category</legend>
                            <a href="{{route('productcategories.index')}}" class="hover:underline">Create
                                One</a>
                        </div>
                        <select name="product_category_id"
                            class="select w-full">
                            <option disabled selected>Pick a browser</option>
                            @foreach ($categories as $category)
                            <option @if($category->id == $product->product_category_id) selected @endif
                                value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        <div id="product_category_id" class="text-red-500 error-message"></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Name</legend>
                        <input name="name" type="text" value="{{old('name') ? old('name') : $product->name}}"
                            class="input w-full" placeholder="Type here" />
                        <div id="name" class="text-red-500 error-message"></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Description</legend>
                        <textarea class="textarea h-24 w-full"
                            name="description"
                            placeholder="Bio">{{old('description') ? old('description') : $product->description}}</textarea>
                        <div id="description" class="text-red-500 error-message"></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">SKU</legend>
                        <input name="sku" type="text" value="{{old('sku') ? old('sku') : $product->sku}}"
                            class="input w-full" placeholder="Type here" />
                        <div id="sku" class="text-red-500 error-message"></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Quantity</legend>
                        <input inputmode="numeric" name="quantity" type="text"
                            value="{{old('quantity') ? old('quantity') : $product->quantity}}"
                            class="input w-full" placeholder="Type here" />
                        <div id="quantity" class="text-red-500 error-message"></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Buy Price</legend>
                        <input inputmode="numeric" name="buy_price" type="text"
                            value="{{old('buy_price') ? old('buy_price') : $product->buy_price}}"
                            class="input w-full" placeholder="Type here" />
                        <div id="buy_price" class="text-red-500 error-message"></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Sell Price</legend>
                        <input inputmode="numeric" name="sell_price" type="text"
                            value="{{old('sell_price') ? old('sell_price') : $product->sell_price}}"
                            class="input w-full"
                            placeholder="Type here" />
                        <div id="sell_price" class="text-red-500 error-message"></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Pick a file</legend>
                        <input type="file" name="image"
                            onchange=" document.getElementById('modalPreviewProduct').src = window.URL.createObjectURL(this.files[0])"
                            class="file-input w-full" />
                        <label class="label">Max size 1MB</label>
                        <div id="image" class="text-red-500 error-message"></div>
                        <div class="border max-w-24 min-h-24 max-h-24 mb-2">
                            <img id="modalPreviewProduct" class="w-full h-full object-cover object-center" id="preview"
                                src="{{$product->image ? asset('storage/' . $product->image)  : 'https://img.icons8.com/liquid-glass/48/no-image.png'}}"
                                alt="preview">
                        </div>
                    </fieldset>

                    <button class="btn btn-primary w-full my-3">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection