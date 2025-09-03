<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalOrders = Order::when(in_array(Auth::user()->role, ['admin', 'cashier']), function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })->count();
        $totalIncome = Order::with('details.product')->when(in_array(Auth::user()->role, ['admin', 'cashier']), function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })->get()->sum(function ($order) {
            return $order->details->sum(function ($detail) {
                return $detail->quantity * $detail->product->price;
            });
        });

        $totalServiceHistories = ServiceHistory::when(in_array(Auth::user()->role, ['admin', 'cashier']), function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })->count();

        $totalServiceIncome = ServiceHistory::with('details')->where('status', 'done')->get()->sum(function ($serviceHistory) {
            return $serviceHistory->details->sum('price');
        });

        $recentOrders = Order::when(in_array(Auth::user()->role, ['admin', 'cashier']), function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })->with('customer')->latest()->take(5)->get();
        $recentServiceHistories = ServiceHistory::when(in_array(Auth::user()->role, ['admin', 'cashier']), function ($query) {
            return $query->where('user_id', Auth::user()->id);
        })->with('customer')->latest()->take(5)->get();

        $dbDriver = config('database.connections.' . config('database.default') . '.driver');

        $monthExpression = $dbDriver === 'sqlite' ? 'strftime("%m", orders.created_at)' : 'MONTH(orders.created_at)';
        $monthlyIncome = Order::when(in_array(Auth::user()->role, ['adnin', 'cashier']), function ($query) {
            return $query->where('orders.user_id', Auth::user()->id);
        })->select(
            DB::raw('sum(order_details.quantity * products.price) as total'),
            DB::raw("$monthExpression as month")
        )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->whereYear('orders.created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthExpression = $dbDriver === 'sqlite' ? 'strftime("%m", service_histories.created_at)' : 'MONTH(service_histories.created_at)';
        $monthlyServiceIncome = ServiceHistory::when(in_array(Auth::user()->role, ['adnin', 'cashier']), function ($query) {
            return $query->where('service_histories.user_id', Auth::user()->id);
        })->select(
            DB::raw('sum(service_details.price) as total'),
            DB::raw("$monthExpression as month")
        )
            ->join('service_details', 'service_histories.id', '=', 'service_details.service_history_id')
            ->whereYear('service_histories.created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyIncomeData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyIncomeData[$i] = $monthlyIncome->get($i, 0) + $monthlyServiceIncome->get($i, 0);
        }

        $monthExpression = $dbDriver === 'sqlite' ? 'strftime("%m", created_at)' : 'MONTH(created_at)';
        $monthlyOrders = Order::when(in_array(Auth::user()->role, ['adnin', 'cashier']), function ($query) {
            return $query->where('orders.user_id', Auth::user()->id);
        })->select(
            DB::raw('count(id) as total'),
            DB::raw("$monthExpression as month")
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyOrdersData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyOrdersData[$i] = $monthlyOrders->get($i, 0);
        }

        $monthExpression = $dbDriver === 'sqlite' ? 'strftime("%m", created_at)' : 'MONTH(created_at)';
        $monthlyServiceHistories = ServiceHistory::when(in_array(Auth::user()->role, ['adnin', 'cashier']), function ($query) {
            return $query->where('service_histories.user_id', Auth::user()->id);
        })->select(
            DB::raw('count(id) as total'),
            DB::raw("$monthExpression as month")
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyServiceHistoriesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyServiceHistoriesData[$i] = $monthlyServiceHistories->get($i, 0);
        }

        return view('dashboard.index', compact(
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'totalIncome',
            'totalServiceHistories',
            'totalServiceIncome',
            'recentOrders',
            'recentServiceHistories',
            'monthlyIncomeData',
            'monthlyOrdersData',
            'monthlyServiceHistoriesData'
        ));
    }
}
