@extends('templates.base')
@section('title', 'Users Management')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />
            <div class="flex justify-between">
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-title">Total Users</div>
                        <div class="stat-value text-center">{{$users->count()}}</div>
                        {{-- <div class="stat-desc"></div> --}}
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{route('users.create')}}" class="btn btn-outline btn-primary"><i
                            class="bi bi-plus text-2xl"></i></a>
                </div>
            </div>
            @if (session()->has('success'))
            <div role="alert" class="alert alert-success max-w-sm ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="dark:text-white">{{session('success')}}</span>
            </div>
            @endif

            @if(session()->has('error'))
            <div role="alert" class="alert alert-error">
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
                @forelse ($users as $user)
                <li class="list-row">
                    <div class="flex items-center">
                        <i class="bi bi-person text-2xl"></i>
                    </div>
                    <div>
                        <div>{{$user->name}}
                            @if ($user->role == 'superadmin')
                            <div class="badge badge-dash badge-warning">SuperAdmin</div>
                            @elseif ($user->role == 'admin')
                            <div class="badge badge-dash badge-primary">Admin</div>
                            @elseif ($user->role == 'cashier')
                            <div class="badge badge-dash badge-success">Cashier</div>
                            @endif
                        </div>
                        @if(auth()->user()->role == 'superadmin' || $user->role != 'superadmin')
                        <div class="text-xs uppercase font-semibold opacity-60">{{$user->email}}</div>
                        @endif
                        <div class="text-xs opacity-60">{{$user->created_at->diffForHumans()}}
                        </div>
                    </div>
                    <div>
                        <form action="{{route('users.activate', [$user->id])}}" method="post" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn btn-xs @if($user->active) btn-warning @else btn-success @endif  text-white block mb-2">@if($user->active)
                                Deactivate @else Activate @endif</button>
                        </form>
                        @if($user->orders()->count() == 0 || $user->customers()->count() == 0 ||
                        $user->products()->count() == 0)
                        <form action="{{route('users.destroy', [$user->id])}}" method="post" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-error  text-white block">Delete</button>
                        </form>
                        @endif

                    </div>
                </li>

                @empty
                <div class="text-center text-lg">No users found</div>
                @endforelse

            </ul>
            {{$users->links()}}
            {{-- list end --}}

            {{-- table start --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <td>{{$user->name}} <div class="badge badge-soft badge-accent">Active</div>
                                @if ($user->role == 'superadmin')
                                <div class="badge badge-dash badge-warning">SuperAdmin</div>
                                @elseif ($user->role == 'admin')
                                <div class="badge badge-dash badge-primary">Admin</div>
                                @elseif ($user->role == 'cashier')
                                <div class="badge badge-dash badge-success">Cashier</div>
                                @endif
                            </td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->created_at->diffForHumans()}}</td>
                            <td>
                                <div>
                                    <form action="{{route('users.activate', [$user->id])}}" method="post"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-xs @if($user->active) btn-warning @else btn-success @endif  text-white block mb-2">@if($user->active)
                                            Deactivate @else Activate @endif</button>
                                    </form>
                                    @if($user->orders()->count() == 0 || $user->customers()->count() == 0 ||
                                    $user->products()->count() == 0)
                                    <form action="{{route('users.destroy', [$user->id])}}" method="post" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-xs btn-error  text-white block">Delete</button>
                                    </form>
                                    @endif

                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{$users->links()}}
            </div>
            {{-- table end --}}

        </div>
    </div>
</div>
@endsection