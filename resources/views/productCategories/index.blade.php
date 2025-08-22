@extends('templates.base')
@section('title', 'Product Categories Management')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />
            <div class="flex justify-between">
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-title">Total Categories</div>
                        <div class="stat-value text-center">{{$productCategories->count()}}</div>
                        {{-- <div class="stat-desc"></div> --}}
                    </div>
                </div>
                <div class="flex items-center">
                    <div id="addNewCategory" onclick="updateCategoryModal.showModal()"
                        class="btn btn-outline btn-primary"><i class="bi bi-plus text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- @if (session()->has('success'))
            <div role="alert" class="alert alert-success max-w-sm ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="dark:text-white">{{session('success')}}</span>
            </div>

            @endif --}}

            {{-- list start --}}
            <ul class="list bg-base-100 rounded-box shadow-md lg:hidden">
                @forelse ($productCategories as $category)
                <li class="list-row">
                    <div class="flex items-center">
                        @if ($category->icon)
                        <img class="size-10 rounded-box"
                            src="{{ asset('storage/'.$category->icon) }}" />
                        @else
                        <div class="size-10 rounded-box bg-gray-200 flex items-center justify-center">
                            <i class="bi bi-image text-2xl text-gray-400"></i>
                        </div>
                        @endif
                    </div>
                    <div>
                        <div>{{$category->name}}</div>
                        <div class="text-xs opacity-60">{{$category->products()->count()}}
                            Products
                        </div>
                        <div onclick="updateCategoryModal.showModal()" data-id="{{$category->id}}"
                            data-name="{{$category->name}}" data-icon="{{$category->icon}}"
                            data-action="{{route('productcategories.update', $category->id)}}"
                            class="text-xs text-primary mt-2 updateCategoryClick">Edit</div>
                    </div>
                    @if ($category->products()->count() == 0)
                    <form action="{{route('productcategories.destroy', $category->id)}}" method="post" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')"
                            class="btn btn-xs btn-error border-0 h-full btn-outline text-white block"><i
                                class="bi bi-trash text-2xl"></i></button>
                    </form>
                    @endif
                </li>

                @empty
                <div class="text-center text-lg">No categories found</div>
                @endforelse

            </ul>
            {{-- list end --}}

            {{-- table start --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Products Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productCategories as $category)
                        <tr>
                            <th>
                                @if ($category->icon)
                                <img class="size-10 rounded-box"
                                    src="{{ asset('storage/'.$category->icon) }}" />
                                @else
                                <div class="size-10 rounded-box bg-gray-200 flex items-center justify-center">
                                    <i class="bi bi-image text-2xl text-gray-400"></i>
                                </div>
                                @endif
                            </th>
                            <td>{{$category->name}}

                            </td>
                            <td>{{$category->products()->count()}}</td>
                            <td class="flex gap-1">
                                <div onclick="updateCategoryModal.showModal()" data-id="{{$category->id}}"
                                    data-name="{{$category->name}}" data-icon="{{$category->icon}}"
                                    data-action="{{route('productcategories.update', $category->id)}}"
                                    class="btn btn-xs btn-success btn-outline text-white border-0 h-full updateCategoryClick">
                                    <i class="bi bi-pencil text-2xl"></i>
                                </div>
                                @if ($category->products()->count() == 0)
                                <form action="{{route('productcategories.destroy', $category->id)}}" method="post"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                        class="btn btn-xs btn-error border-0 h-full btn-outline text-white block"><i
                                            class="bi bi-trash text-2xl"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No categories found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- table end --}}

            <x-update-category-modal />
        </div>
    </div>
</div>
@endsection