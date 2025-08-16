@extends('templates.base')
@section('title', 'Dashboard')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />

            {{-- Stats --}}
            <div class="stats shadow w-full my-4">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-people text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Customers</div>
                    <div class="stat-value">{{ $totalCustomers }}</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-box-seam text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Products</div>
                    <div class="stat-value">{{ $totalProducts }}</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-cart text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-cash-coin text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Order Income</div>
                    <div class="stat-value">Rp. {{ number_format($totalIncome, 2, ',', '.') }}</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-tools text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Service Histories</div>
                    <div class="stat-value">{{ $totalServiceHistories }}</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="bi bi-wallet2 text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Service Income</div>
                    <div class="stat-value">Rp. {{ number_format($totalServiceIncome, 2, ',', '.') }}</div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="card bg-base-100 shadow-xl md:col-span-2">
                    <div class="card-body">
                        <h2 class="card-title">Monthly Income ({{ date('Y') }})</h2>
                        <canvas id="monthlyIncomeChart"></canvas>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Monthly Orders ({{ date('Y') }})</h2>
                        <canvas id="monthlyOrdersChart"></canvas>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Monthly Service Histories ({{ date('Y') }})</h2>
                        <canvas id="monthlyServiceHistoriesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                {{-- Recent Orders --}}
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Recent Orders</h2>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders as $order)
                                    <tr>
                                        <th>{{ $order->id }}</th>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ $order->created_at->format('d F Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No recent orders.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Recent Service Histories --}}
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Recent Service Histories</h2>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentServiceHistories as $serviceHistory)
                                    <tr>
                                        <th>{{ $serviceHistory->id }}</th>
                                        <td>{{ $serviceHistory->customer->name }}</td>
                                        <td>{{ $serviceHistory->status }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No recent service histories.</td>
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
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const monthlyIncomeCtx = document.getElementById('monthlyIncomeChart').getContext('2d');
            const monthlyIncomeChart = new Chart(monthlyIncomeCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Income',
                        data: {{ json_encode(array_values($monthlyIncomeData)) }},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const monthlyOrdersCtx = document.getElementById('monthlyOrdersChart').getContext('2d');
            const monthlyOrdersChart = new Chart(monthlyOrdersCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Orders',
                        data: {{ json_encode(array_values($monthlyOrdersData)) }},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const monthlyServiceHistoriesCtx = document.getElementById('monthlyServiceHistoriesChart').getContext('2d');
            const monthlyServiceHistoriesChart = new Chart(monthlyServiceHistoriesCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Service Histories',
                        data: {{ json_encode(array_values($monthlyServiceHistoriesData)) }},
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
</script>
@endsection