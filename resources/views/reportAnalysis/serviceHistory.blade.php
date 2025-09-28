@extends('templates.base')
@section('title', 'Service History Report')

@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />

            <div class="px-2 mx-auto">
                <h1 class="text-xl lg:text-2xl font-bold mb-8">Report & Analysis of Service History</h1>

                {{-- Monthly Section --}}
                <div class="mb-12">
                    <h2 class="text-xl lg:text-2xl font-bold mb-4">Report of this Month</h2>
                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                        <div class="stat">
                            <div class="stat-title">Gross Revenue</div>
                            <div class="stat-value text-primary">Rp {{ number_format($totalThisMonthServiceGrossIncome
                                ?? 0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Total {{ $totalThisMonthServices }} services</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Net Revenue</div>
                            <div class="stat-value text-secondary">Rp {{ number_format($totalThisMonthServiceNetIncome
                                ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Expenses</div>
                            <div class="stat-value">Rp {{ number_format($monthExpenses ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Loss</div>
                            <div class="stat-value text-error">Rp {{ number_format($totalThisMonthLoss ?? 0, 0, ',',
                                '.') }}</div>
                            <div class="stat-desc">From services where cost > price</div>
                        </div>
                    </div>
                </div>

                {{-- Weekly Section --}}
                <div class="mb-12">
                    <h2 class="text-xl lg:text-2xl font-bold mb-4">Report of this Week</h2>
                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                        <div class="stat">
                            <div class="stat-title">Gross Revenue</div>
                            <div class="stat-value text-primary">Rp {{ number_format($totalThisWeekServiceGrossIncome ??
                                0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Total {{ $totalThisWeekServices }} services</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Net Revenue</div>
                            <div class="stat-value text-secondary">Rp {{ number_format($totalThisWeekServiceNetIncome ??
                                0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Expenses</div>
                            <div class="stat-value">Rp {{ number_format($weekExpenses ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Loss</div>
                            <div class="stat-value text-error">Rp {{ number_format($totalThisWeekLoss ?? 0, 0, ',', '.')
                                }}</div>
                            <div class="stat-desc">From services where cost > price</div>
                        </div>
                    </div>
                </div>

                {{-- Daily Section --}}
                <div class="mb-12">
                    <h2 class="text-xl lg:text-2xl font-bold mb-4">Report of today</h2>
                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                        <div class="stat">
                            <div class="stat-title">Gross Revenue</div>
                            <div class="stat-value text-primary">Rp {{ number_format($totalThisDayServiceGrossIncome ??
                                0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Total {{ $totalThisDayServices }} services</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Net Revenue</div>
                            <div class="stat-value text-secondary">Rp {{ number_format($totalThisDayServiceNetIncome ??
                                0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Expenses</div>
                            <div class="stat-value">Rp {{ number_format($dayExpenses ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Loss</div>
                            <div class="stat-value text-error">Rp {{ number_format($totalThisDayLoss ?? 0, 0, ',', '.')
                                }}</div>
                            <div class="stat-desc">From services where cost > price</div>
                        </div>
                    </div>
                </div>

                {{-- Top Services by Net Income Table --}}
                <div class="mt-12">
                    <h2 class="text-2xl lg:text-2xl font-bold mb-4">Top 20 Services by Net Income (All Time)</h2>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Net Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($servicesByNetIncome as $item)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td><a href="{{ route('servicehistories.show', $item->id) }}"
                                            class="link text-white">{{ $item->invoice_number }}</a></td>
                                    <td>{{ $item->customer_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                    <td>Rp. {{ number_format($item->net_income, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No service history data available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection