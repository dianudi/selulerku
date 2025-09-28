@extends('templates.base')
@section('title', 'Report & Analysis')

@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />

            <div class="px-2 mx-auto">
                <h1 class="text-xl lg:text-2xl font-bold mb-8">Report & Analysis of Product Sales</h1>

                {{-- Monthly Section --}}
                <div class="mb-12">
                    <h2 class="text-xl lg:text-2xl font-bold mb-4">Report of this Month</h2>
                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                        <div class="stat">
                            <div class="stat-title">Gross Revenue</div>
                            <div class="stat-value text-primary">Rp {{ number_format($totalThisMonthOrderGrossIncome ??
                                0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Total {{ $totalThisMonthOrders }} transactions</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Net Revenue</div>
                            <div class="stat-value text-secondary">Rp {{ number_format($totalThisMonthOrderNetIncome ??
                                0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Avg: Rp {{ number_format($averageThisMonthNetIncome ?? 0, 0, ',',
                                '.') }}/transaction</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Expenses</div>
                            <div class="stat-value">Rp {{ number_format($monthExpenses ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Highest Net Revenue</div>
                            <div class="stat-value">Rp {{ number_format($maxNetIncomeThisMonth ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="stat-desc">Lowest: Rp {{ number_format($minNetIncomeThisMonth ?? 0, 0, ',',
                                '.') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Weekly Section --}}
                <div class="mb-12">
                    <h2 class="text-xl lg:text-2xl font-bold mb-4">Report of this Week</h2>
                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                        <div class="stat">
                            <div class="stat-title">Gross Revenue</div>
                            <div class="stat-value text-primary">Rp {{ number_format($totalThisWeekOrderGrossIncome ??
                                0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Total {{ $totalThisWeekOrders }} transactions</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Net Revenue</div>
                            <div class="stat-value text-secondary">Rp {{ number_format($totalThisWeekOrderNetIncome ??
                                0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Avg: Rp {{ number_format($averageThisWeekNetIncome ?? 0, 0, ',', '.')
                                }}/transaction</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Expenses</div>
                            <div class="stat-value">Rp {{ number_format($weekExpenses ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Highest Net Revenue</div>
                            <div class="stat-value">Rp {{ number_format($maxNetIncomeThisWeek ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="stat-desc">Lowest: Rp {{ number_format($minNetIncomeThisWeek ?? 0, 0, ',',
                                '.') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Daily Section --}}
                <div class="mb-12">
                    <h2 class="text-xl lg:text-2xl font-bold mb-4">Report of today</h2>
                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                        <div class="stat">
                            <div class="stat-title">Gross Revenue</div>
                            <div class="stat-value text-primary">Rp {{ number_format($totalThisDayOrderGrossIncome ?? 0,
                                0, ',', '.') }}</div>
                            <div class="stat-desc">Total {{ $totalThisDayOrders }} transactions</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Net Revenue</div>
                            <div class="stat-value text-secondary">Rp {{ number_format($totalThisDayOrderNetIncome ?? 0,
                                0, ',', '.') }}</div>
                            <div class="stat-desc">Avg: Rp {{ number_format($averageThisDayNetIncome ?? 0, 0, ',', '.')
                                }}/transaction</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Total Expenses</div>
                            <div class="stat-value">Rp {{ number_format($dayExpenses ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Highest Net Revenue</div>
                            <div class="stat-value">Rp {{ number_format($maxNetIncomeThisDay ?? 0, 0, ',', '.') }}</div>
                            <div class="stat-desc">Lowest: Rp {{ number_format($minNetIncomeThisDay ?? 0, 0, ',', '.')
                                }}</div>
                        </div>
                    </div>
                </div>

                {{-- Best Sellers Table --}}
                <div class="mt-12">
                    <h2 class="text-2xl lg:text-2xl font-bold mb-4">Best Sellers (Top 10 All Time)</h2>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Sell Price</th>
                                    <th>Total Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bestSellingProducts as $item)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $item->product->name ?? 'Product not found' }}</td>
                                    <td>Rp. {{ number_format($item->product->sell_price, 0, ',', '.') }}</td>
                                    <td>{{ $item->total_quantity_sold }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data penjualan.</td>
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