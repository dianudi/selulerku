@extends('templates.base')
@section('title', 'Create New Product')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />
            <div class="w-full px-2">
                <h1 class="text-lg md:text-2xl font-bold text-center">Create New Product</h1>
                <form id="productForm" action="{{route('products.store')}}" class="mx-auto block max-w-lg"
                    method="post">
                    @csrf
                    <fieldset class="fieldset">
                        <div class="flex justify-between">
                            <legend class="fieldset-legend">Category</legend>
                            <a href="{{route('productcategories.index')}}" class="hover:underline">Create
                                One</a>
                        </div>
                        <select name="product_category_id"
                            class="select w-full @error('product_category_id') border-red-500 @enderror">
                            <option disabled selected>Pick a Category</option>
                            @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        {{-- @error('product_category_id') --}}
                        <div id="productErrorCategory" class="text-red-500"></div>
                        {{-- @enderror --}}
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Name</legend>
                        <input name="name" type="text" value="{{old('name')}}"
                            class="input w-full @error('name') border-red-500 @enderror" placeholder="Type here" />
                        {{-- @error('name') --}}
                        <div id="productErrorName" class="text-red-500"></div>
                        {{-- @enderror --}}
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Description</legend>
                        <textarea class="textarea h-24 w-full @error('description') border-red-500 @enderror"
                            name="description" placeholder="Bio">{{old('description')}}</textarea>
                        {{-- @error('description') --}}
                        <div id="productErrorDescription" class="text-red-500"></div>
                        {{-- @enderror --}}
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">SKU</legend>
                        <input name="sku" type="text" value="{{old('sku')}}"
                            class="input w-full @error('sku') border-red-500 @enderror" placeholder="Type here" />
                        {{-- @error('sku') --}}
                        <div id="productErrorSku" class="text-red-500"></div>
                        {{-- @enderror --}}
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Quantity</legend>
                        <input inputmode="numeric" name="quantity" type="text" value="{{old('quantity')}}"
                            class="input w-full @error('quantity') border-red-500 @enderror" placeholder="Type here" />
                        {{-- @error('quantity') --}}
                        <div id="productErrorQuantity" class="text-red-500"></div>
                        {{-- @enderror --}}
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Price</legend>
                        <input inputmode="numeric" name="price" type="text" value="{{old('price')}}"
                            class="input w-full @error('price') border-red-500 @enderror" placeholder="Type here" />
                        {{-- @error('price') --}}
                        <div id="productErrorPrice" class="text-red-500"></div>
                        {{-- @enderror --}}
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Pick a file</legend>
                        <input type="file" name="image"
                            onchange=" document.getElementById('modalPreviewProduct').src = window.URL.createObjectURL(this.files[0])"
                            class="file-input w-full @error('image') border-red-500 @enderror" />
                        <label class="label">Max size 1MB</label>
                        {{-- @error('image') --}}
                        <div id="productErrorImage" class="text-red-500"></div>
                        {{-- @enderror --}}
                        <div class="border max-w-24 min-h-24 max-h-24 mb-2">
                            <img id="modalPreviewProduct" class="w-full h-full object-cover object-center" id="preview"
                                src="" alt="preview">
                        </div>
                    </fieldset>

                    <button class="btn btn-primary w-full my-3">Create Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection