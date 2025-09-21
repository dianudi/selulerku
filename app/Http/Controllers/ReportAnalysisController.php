<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ServiceHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportAnalysisController extends Controller
{
    public function __construct()
    {
        if (Gate::denies('superadmin')) {
            return abort(403);
        }
    }

    public function productSales()
    {
        // A helper function to avoid repeating the main logic for fetching income stats
        $calculateStats = function ($dateQuery) {
            return OrderDetail::whereHas('order', $dateQuery)
                ->select(
                    DB::raw('SUM(immutable_sell_price) as gross_income'),
                    DB::raw('SUM(immutable_buy_price) as total_cost')
                )->first();
        };

        // Define date range queries for each period to be reused
        $allTimeQuery = function ($query) { /* No date conditions for all time */
        };
        $thisMonthQuery = function ($query) {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        };
        $thisWeekQuery = function ($query) {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        };
        $thisDayQuery = function ($query) {
            $query->whereDate('created_at', today());
        };

        // Calculate income stats for each period using the helper
        $allStats = $calculateStats($allTimeQuery);
        $monthStats = $calculateStats($thisMonthQuery);
        $weekStats = $calculateStats($thisWeekQuery);
        $dayStats = $calculateStats($thisDayQuery);

        // Order Analysis
        $totalAllOrders = Order::count();
        $totalThisMonthOrders = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $totalThisWeekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $totalThisDayOrders = Order::whereDate('created_at', today())->count();

        // Gross Income
        $totalAllOrderGrossIncome = $allStats->gross_income ?? 0;
        $totalThisMonthOrderGrossIncome = $monthStats->gross_income ?? 0;
        $totalThisWeekOrderGrossIncome = $weekStats->gross_income ?? 0;
        $totalThisDayOrderGrossIncome = $dayStats->gross_income ?? 0;

        // Net Income
        $totalAllOrderNetIncome = $totalAllOrderGrossIncome - ($allStats->total_cost ?? 0);
        $totalThisMonthOrderNetIncome = $totalThisMonthOrderGrossIncome - ($monthStats->total_cost ?? 0);
        $totalThisWeekOrderNetIncome = $totalThisWeekOrderGrossIncome - ($weekStats->total_cost ?? 0);
        $totalThisDayOrderNetIncome = $totalThisDayOrderGrossIncome - ($dayStats->total_cost ?? 0);

        // Average Gross Income
        $averageAllGrossIncome = $totalAllOrders > 0 ? $totalAllOrderGrossIncome / $totalAllOrders : 0;
        $averageThisMonthGrossIncome = $totalThisMonthOrders > 0 ? $totalThisMonthOrderGrossIncome / $totalThisMonthOrders : 0;
        $averageThisWeekGrossIncome = $totalThisWeekOrders > 0 ? $totalThisWeekOrderGrossIncome / $totalThisWeekOrders : 0;
        $averageThisDayGrossIncome = $totalThisDayOrders > 0 ? $totalThisDayOrderGrossIncome / $totalThisDayOrders : 0;

        // Average Net Income
        $averageAllNetIncome = $totalAllOrders > 0 ? $totalAllOrderNetIncome / $totalAllOrders : 0;
        $averageThisMonthNetIncome = $totalThisMonthOrders > 0 ? $totalThisMonthOrderNetIncome / $totalThisMonthOrders : 0;
        $averageThisWeekNetIncome = $totalThisWeekOrders > 0 ? $totalThisWeekOrderNetIncome / $totalThisWeekOrders : 0;
        $averageThisDayNetIncome = $totalThisDayOrders > 0 ? $totalThisDayOrderNetIncome / $totalThisDayOrders : 0;

        // Max Gross Income
        $maxGrossIncomeThisMonth = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfMonth(), now()->endOfMonth()])->selectRaw('SUM(order_details.immutable_sell_price) as order_gross')->groupBy('order_details.order_id')->orderByDesc('order_gross')->first()?->order_gross;
        $maxGrossIncomeThisWeek = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()])->selectRaw('SUM(order_details.immutable_sell_price) as order_gross')->groupBy('order_details.order_id')->orderByDesc('order_gross')->first()?->order_gross;
        $maxGrossIncomeThisDay = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereDate('orders.created_at', today())->selectRaw('SUM(order_details.immutable_sell_price) as order_gross')->groupBy('order_details.order_id')->orderByDesc('order_gross')->first()?->order_gross;

        // Min Gross Income
        $minGrossIncomeThisMonth = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfMonth(), now()->endOfMonth()])->selectRaw('SUM(order_details.immutable_sell_price) as order_gross')->groupBy('order_details.order_id')->orderBy('order_gross', 'asc')->first()?->order_gross;
        $minGrossIncomeThisWeek = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()])->selectRaw('SUM(order_details.immutable_sell_price) as order_gross')->groupBy('order_details.order_id')->orderBy('order_gross', 'asc')->first()?->order_gross;
        $minGrossIncomeThisDay = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereDate('orders.created_at', today())->selectRaw('SUM(order_details.immutable_sell_price) as order_gross')->groupBy('order_details.order_id')->orderBy('order_gross', 'asc')->first()?->order_gross;

        // Max Net Income
        $maxNetIncomeThisMonth = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfMonth(), now()->endOfMonth()])->selectRaw('SUM((order_details.immutable_sell_price - order_details.immutable_buy_price)) as order_net_income')->groupBy('order_details.order_id')->orderByDesc('order_net_income')->first()?->order_net_income;
        $maxNetIncomeThisWeek = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()])->selectRaw('SUM((order_details.immutable_sell_price - order_details.immutable_buy_price)) as order_net_income')->groupBy('order_details.order_id')->orderByDesc('order_net_income')->first()?->order_net_income;
        $maxNetIncomeThisDay = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereDate('orders.created_at', today())->selectRaw('SUM((order_details.immutable_sell_price - order_details.immutable_buy_price)) as order_net_income')->groupBy('order_details.order_id')->orderByDesc('order_net_income')->first()?->order_net_income;

        // Min Net Income
        $minNetIncomeThisMonth = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfMonth(), now()->endOfMonth()])->selectRaw('SUM((order_details.immutable_sell_price - order_details.immutable_buy_price)) as order_net_income')->groupBy('order_details.order_id')->orderBy('order_net_income', 'asc')->first()?->order_net_income;
        $minNetIncomeThisWeek = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()])->selectRaw('SUM((order_details.immutable_sell_price - order_details.immutable_buy_price)) as order_net_income')->groupBy('order_details.order_id')->orderBy('order_net_income', 'asc')->first()?->order_net_income;
        $minNetIncomeThisDay = DB::table('order_details')->join('orders', 'order_details.order_id', '=', 'orders.id')->whereDate('orders.created_at', today())->selectRaw('SUM((order_details.immutable_sell_price - order_details.immutable_buy_price)) as order_net_income')->groupBy('order_details.order_id')->orderBy('order_net_income', 'asc')->first()?->order_net_income;

        // list of best selling product
        $bestSellingProducts = OrderDetail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity_sold')
        )
            ->with('product:id,name,sell_price') // Eager load product name for efficiency
            ->groupBy('product_id')
            ->orderByDesc('total_quantity_sold')
            ->limit(10)
            ->get();

        return view('reportAnalysis.productSales', compact(
            'totalAllOrders',
            'totalThisMonthOrders',
            'totalThisWeekOrders',
            'totalThisDayOrders',
            'totalAllOrderGrossIncome',
            'totalThisMonthOrderGrossIncome',
            'totalThisWeekOrderGrossIncome',
            'totalThisDayOrderGrossIncome',
            'totalAllOrderNetIncome',
            'totalThisMonthOrderNetIncome',
            'totalThisWeekOrderNetIncome',
            'totalThisDayOrderNetIncome',
            'averageAllGrossIncome',
            'averageThisMonthGrossIncome',
            'averageThisWeekGrossIncome',
            'averageThisDayGrossIncome',
            'averageAllNetIncome',
            'averageThisMonthNetIncome',
            'averageThisWeekNetIncome',
            'averageThisDayNetIncome',
            'bestSellingProducts',
            'maxGrossIncomeThisMonth',
            'maxGrossIncomeThisWeek',
            'maxGrossIncomeThisDay',
            'minGrossIncomeThisMonth',
            'minGrossIncomeThisWeek',
            'minGrossIncomeThisDay',
            'maxNetIncomeThisMonth',
            'maxNetIncomeThisWeek',
            'maxNetIncomeThisDay',
            'minNetIncomeThisMonth',
            'minNetIncomeThisWeek',
            'minNetIncomeThisDay'
        ));
    }

    public function serviceHistory()
    {
        // A helper function to avoid repeating the main logic for fetching income stats
        $calculateStats = function ($dateQuery) {
            return DB::table('service_details')
                ->join('service_histories', 'service_details.service_history_id', '=', 'service_histories.id')
                ->where($dateQuery)
                ->select(
                    DB::raw('SUM(service_details.price) as gross_income'),
                    DB::raw('SUM(service_details.cost_price) as total_cost')
                )->first();
        };

        // A helper function to calculate total loss
        $calculateLoss = function ($dateQuery) {
            return DB::table('service_details')
                ->join('service_histories', 'service_details.service_history_id', '=', 'service_histories.id')
                ->where($dateQuery)
                ->where('service_details.cost_price', '>', DB::raw('service_details.price'))
                ->sum(DB::raw('service_details.cost_price - service_details.price'));
        };

        // Define date range queries for each period to be reused
        $thisMonthQuery = function ($query) {
            $query->whereBetween('service_histories.created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        };
        $thisWeekQuery = function ($query) {
            $query->whereBetween('service_histories.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        };
        $thisDayQuery = function ($query) {
            $query->whereDate('service_histories.created_at', today());
        };

        // Calculate income stats for each period using the helper
        $monthStats = $calculateStats($thisMonthQuery);
        $weekStats = $calculateStats($thisWeekQuery);
        $dayStats = $calculateStats($thisDayQuery);

        // Calculate losses for each period
        $totalThisMonthLoss = $calculateLoss($thisMonthQuery);
        $totalThisWeekLoss = $calculateLoss($thisWeekQuery);
        $totalThisDayLoss = $calculateLoss($thisDayQuery);

        // Service History Analysis
        $totalThisMonthServices = ServiceHistory::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $totalThisWeekServices = ServiceHistory::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $totalThisDayServices = ServiceHistory::whereDate('created_at', today())->count();

        // Gross Income
        $totalThisMonthServiceGrossIncome = $monthStats->gross_income ?? 0;
        $totalThisWeekServiceGrossIncome = $weekStats->gross_income ?? 0;
        $totalThisDayServiceGrossIncome = $dayStats->gross_income ?? 0;

        // Net Income
        $totalThisMonthServiceNetIncome = $totalThisMonthServiceGrossIncome - ($monthStats->total_cost ?? 0);
        $totalThisWeekServiceNetIncome = $totalThisWeekServiceGrossIncome - ($weekStats->total_cost ?? 0);
        $totalThisDayServiceNetIncome = $totalThisDayServiceGrossIncome - ($dayStats->total_cost ?? 0);

        // Get top 20 services by net income (all time)
        $servicesByNetIncome = DB::table('service_details')
            ->join('service_histories', 'service_details.service_history_id', '=', 'service_histories.id')
            ->join('customers', 'service_histories.customer_id', '=', 'customers.id')
            ->select(
                'service_histories.id',
                'service_histories.invoice_number',
                'service_histories.created_at',
                'customers.name as customer_name',
                DB::raw('SUM(service_details.price) - SUM(service_details.cost_price) as net_income')
            )
            ->groupBy('service_histories.id', 'service_histories.invoice_number', 'service_histories.created_at', 'customers.name')
            ->orderByDesc('net_income')
            ->limit(20)
            ->get();

        return view('reportAnalysis.serviceHistory', compact(
            'totalThisMonthServices',
            'totalThisWeekServices',
            'totalThisDayServices',
            'totalThisMonthServiceGrossIncome',
            'totalThisWeekServiceGrossIncome',
            'totalThisDayServiceGrossIncome',
            'totalThisMonthServiceNetIncome',
            'totalThisWeekServiceNetIncome',
            'totalThisDayServiceNetIncome',
            'totalThisMonthLoss',
            'totalThisWeekLoss',
            'totalThisDayLoss',
            'servicesByNetIncome'
        ));
    }
}
