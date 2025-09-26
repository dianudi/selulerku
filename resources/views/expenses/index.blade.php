@extends('templates.base')
@section('title', 'Expenses ')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Expenses</h1>
                    <p class="text-sm opacity-60">Total {{ $expenses->total() }} expenses</p>
                </div>
                <a href="{{route('expenses.create')}}"><i class="bi bi-plus text-2xl"></i></a>
            </div>
            {{-- table start --}}
            <div class="overflow-x-auto hidden md:block">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                        <tr>
                            <th><a href="{{ route('expenses.show', $expense->id) }}">{{ $expense->description }}</a>
                            </th>
                            <td>{{ $expense->category }}</td>
                            <td>Rp. {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td>{{$expense->created_at->format('d M Y') }}</td>
                            <td>{{ $expense->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No expenses found</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            {{-- table end --}}

            {{-- list start --}}
            <div class="grid grid-cols-1 md:hidden gap-4 mt-4">
                @forelse ($expenses as $expense)
                    <a href="{{ route('expenses.show', $expense->id) }}" class="bg-base-100 p-4 rounded-lg shadow">
                        <div class="flex justify-between items-start">
                            <div class="font-bold truncate">{{ $expense->description }}</div>
                            <div class="text-right">
                                <div class="font-semibold">Rp. {{ number_format($expense->amount, 0, ',', '.') }}</div>
                                <div class="text-xs opacity-60">{{ $expense->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="text-sm mt-2">
                            <span class="opacity-60">Category:</span> {{ $expense->category }}
                        </div>
                        <div class="text-sm">
                            <span class="opacity-60">User:</span> {{ $expense->user->name }}
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8">
                        <p>No expenses found</p>
                    </div>
                @endforelse
            </div>
            {{-- list end --}}
        </div>
    </div>
</div>
@endSection