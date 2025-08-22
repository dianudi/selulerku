@extends('templates.base')
@section('title', 'Customer Management')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />
            <div class="flex justify-between">
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-title">Total Customers</div>
                        <div class="stat-value text-center">{{$customers->count()}}</div>
                        {{-- <div class="stat-desc"></div> --}}
                    </div>
                </div>
            </div>

            @if (session()->has('success'))
            <div role="alert" class="alert alert-success max-w-md ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="dark:text-white">{{session('success')}}</span>
            </div>

            @endif

            @if (session()->has('error'))
            <div role="alert" class="alert alert-error max-w-md ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{session('error')}}</span>
            </div>

            @endif

            {{-- list start --}}
            <ul class="list bg-base-100 rounded-box shadow-md lg:hidden">
                @forelse ($customers as $customer)
                <li class="list-row">
                    <div class="flex items-center">
                        <i class="bi bi-person text-2xl"></i>
                    </div>
                    <div>
                        <div><a href="{{route('customers.show', $customer->id)}}"
                                class="hover:underline">{{$customer->name}}</a>
                            @if ($customer->created_at->diffForHumans() == 'just now')
                            <div class="badge badge-dash badge-warning">New</div>
                            @endif
                        </div>
                        <div class="text-xs uppercase font-semibold opacity-60"><a
                                class="hover:underline text-blue-400 text-md"
                                href="tel:{{$customer->phone_number}}">{{$customer->phone_number}}</a></div>
                        <div class="text-xs opacity-60">{{$customer->address}}
                        </div>
                    </div>
                    <form action="{{route('customers.destroy', $customer->id)}}" method="post" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')"
                            class="btn btn-outline border-0 btn-error text-white block"><i
                                class="bi bi-trash text-2xl"></i></button>
                    </form>
                </li>
                @empty
                <div class="text-center text-lg">No customers found</div>
                @endforelse

            </ul>
            {{$customers->links()}}
            {{-- list end --}}

            {{-- table start --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Adress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <td><a href="{{route('customers.show', $customer->id)}}"
                                    class="hover:underline">{{$customer->name}}</a>
                                @if ($customer->created_at->diffForHumans() == 'just now')
                                <div class="badge badge-dash badge-warning">New</div>
                                @endif
                            </td>
                            <td><a class="hover:underline text-blue-400 text-md"
                                    href="tel:{{$customer->phone_number}}">{{$customer->phone_number}}</a></td>
                            <td>{{$customer->address}}</td>
                            <td>
                                <form action="{{route('customers.destroy', $customer->id)}}" method="post"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                        class="btn btn-xs btn-error border-0 h-full btn-outline text-white block"><i
                                            class="bi bi-trash text-2xl"></i></button>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No customers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{$customers->links()}}
            </div>
            {{-- table end --}}

        </div>
    </div>
</div>
@endsection