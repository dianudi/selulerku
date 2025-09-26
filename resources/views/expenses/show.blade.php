@extends('templates.base')
@section('title', 'Detail Expense')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <div class=" bg-base-100 rounded-lg shadow-md ">
                <h1 class="text-2xl font-bold mb-4">Detail Expense</h1>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Description</label>
                        <p class="mt-1 text-lg">{{ $expense->description }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Amount</label>
                        <p class="mt-1 text-lg">Rp. {{ number_format($expense->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Category</label>
                        <p class="mt-1 text-lg">{{ $expense->category }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date</label>
                        <p class="mt-1 text-lg">{{ $expense->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Created by</label>
                        <p class="mt-1 text-lg">{{ $expense->user->name }}</p>
                    </div>

                    @if($expense->receipt_image_path)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Receipt Image</label>
                        <div class="mt-1 border rounded-lg p-2">
                            <img src="{{ asset('storage/' . $expense->receipt_image_path) }}"
                                alt="Receipt for {{ $expense->description }}" class="rounded-lg max-w-xs">
                        </div>
                    </div>
                    @endif
                </div>
                @if(now()->diffInDays($expense->created_at) < 2) <div class="mt-6 flex items-center gap-4">
                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this expense?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error">Delete</button>
                    </form>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection