@extends('templates.base')
@section('title', 'Detail Service Histories')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <div class="w-full">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-center">
                            <h2 class="card-title">Service History Details</h2>
                            <div class="hidden md:flex justify-end gap-2">
                                <button class="btn btn-primary"><i class="bi bi-printer"></i></button>
                                <a href="{{ route('servicehistories.edit', $serviceHistory) }}"
                                    class="btn btn-secondary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('servicehistories.destroy', $serviceHistory) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-bold">Customer:</p>
                                <p>{{ $serviceHistory->customer->name }}</p>
                            </div>
                            <div>
                                <p class="font-bold">Warranty Expired At:</p>
                                <p>{{ $serviceHistory->warranty_expired_at->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="font-bold">Status:</p>
                                <p>{{ $serviceHistory->status }}</p>
                            </div>
                            <div>
                                <p class="font-bold">Invoice Number:</p>
                                <p>{{ $serviceHistory->invoice_number }}</p>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <h3 class="text-lg font-bold">Service Details</h3>

                        {{-- Table for Desktop --}}
                        <div class="overflow-x-auto hidden md:block">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Kind</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceHistory->details as $detail)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $detail->kind }}</td>
                                        <td>{{ $detail->description }}</td>
                                        <td>Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <th>Rp.
                                            {{ number_format($serviceHistory->details->sum('price'), 0, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- List for Mobile --}}
                        <div class="block md:hidden">
                            @foreach ($serviceHistory->details as $detail)
                            <div class="card bg-base-200 shadow-md mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $detail->kind }}</h4>
                                    <p>{{ $detail->description }}</p>
                                    <p class="text-right font-bold">Rp.
                                        {{ number_format($detail->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                            <div class="text-right font-bold text-lg">Total: Rp.
                                {{ number_format($serviceHistory->details->sum('price'), 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="md:hidden flex justify-around gap-2 my-4">
                    <button class="btn btn-primary flex-1"><i class="bi bi-printer"></i> Print</button>
                    <a href="{{ route('servicehistories.edit', $serviceHistory) }}" class="btn btn-secondary flex-1"><i
                            class="bi bi-pencil"></i> Edit</a>
                    <form action="{{ route('servicehistories.destroy', $serviceHistory) }}" method="post"
                        class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error w-full"><i class="bi bi-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection