<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = $request->has('search') ? Order::where('invoice_number', 'like', '%' . $request->search . '%')->when(!in_array(Auth::user()->role, ['admin', 'superadmin']), function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('created_at', 'desc')->paginate(15) : Order::when(!in_array(Auth::user()->role, ['admin', 'superadmin']), function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('created_at', 'desc')->paginate(15);

        $userOrders = Order::when(!in_array(Auth::user()->role, ['admin', 'superadmin']), function ($query) {
            $query->where('user_id', Auth::id());
        });
        $totalRevenue = OrderDetail::whereIn('order_id', $userOrders->clone()->where('status', 'paid')->pluck('id'))->sum('immutable_price');
        $totalOrders = $userOrders->clone()->count();
        $unpaidOrders = $userOrders->clone()->where('status', 'unpaid')->count();
        $paidOrders = $userOrders->clone()->where('status', 'paid')->count();

        return view('orders.index', compact('orders', 'totalRevenue', 'totalOrders', 'unpaidOrders', 'paidOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $order = new Order($request->validated());
                $order->user_id = Auth::id();
                $order->invoice_number = 'INVOICE/' . now()->format('Y/m/d') . '/' . rand(1000, 9999) . '/' . rand(1000, 9999) . '/' . $request->input('customer_id');
                $order->save();

                $details = [];
                foreach ($request->input('details') as $detail) {
                    // Lock the product row to prevent race conditions
                    $product = Product::where('id', $detail['product_id'])->lockForUpdate()->first();

                    // Check if there is enough quantity
                    if ($product->quantity < $detail['quantity']) {
                        // This will automatically trigger a rollback
                        throw new \Exception('Not enough stock for product ' . $product->name);
                    }

                    $product->quantity -= $detail['quantity'];
                    $product->save();

                    $details[] = [
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'immutable_price' => $product->sell_price * $detail['quantity']
                    ];
                }

                $order->details()->createMany($details);
            });
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return abort(403);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        if ($order->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return abort(403);

        $cartData = $order->details->map(function ($detail) {
            return [
                'id' => $detail->product_id,
                'name' => $detail->product->name,
                'price' => $detail->product->sell_price,
                'quantity' => $detail->quantity,
                'image' => $detail->product->image ? asset('storage/' . $detail->product->image) : 'https://img.icons8.com/liquid-glass/200/no-image.png',
            ];
        });

        return view('orders.edit', compact('order', 'cartData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        if ($order->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return abort(403);

        try {
            DB::transaction(function () use ($request, $order) {
                foreach ($order->details as $oldDetail) {
                    Product::where('id', $oldDetail->product_id)->increment('quantity', $oldDetail->quantity);
                }
                $order->details()->delete();
                $order->update($request->validated());

                $newDetailsData = [];
                if ($request->has('details')) {
                    foreach ($request->input('details') as $detail) {
                        // Lock product for update to prevent race conditions
                        $product = Product::where('id', $detail['product_id'])->lockForUpdate()->first();

                        // Check for sufficient stock
                        if (!$product || $product->quantity < $detail['quantity']) {
                            throw new \Exception('Not enough stock for product: ' . ($product->name ?? 'Unknown'));
                        }

                        // Decrement stock
                        $product->decrement('quantity', $detail['quantity']);

                        // Prepare new detail data
                        $newDetailsData[] = [
                            'product_id' => $detail['product_id'],
                            'quantity' => $detail['quantity'],
                            'immutable_price' => $product->sell_price * $detail['quantity'],
                        ];
                    }
                }

                if (!empty($newDetailsData)) {
                    $order->details()->createMany($newDetailsData);
                }
            });
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error updating order: ' . $th->getMessage())->withInput();
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return abort(403);
        // Only allow deletion if the order was created today.
        if (!$order->created_at->isToday()) {
            return back()->with('error', 'Order can only be deleted on the same day it was created.');
        }

        foreach ($order->details as $detail) {
            Product::where('id', $detail->product_id)->increment('quantity', $detail->quantity);
        }

        $order->details()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }

    public function print(Order $order)
    {
        if ($order->user_id !== Auth::user()->id) return abort(403);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('orders.receipt', compact('order'));
        return $pdf->stream('receipt-' . str_replace('/', '-', $order->invoice_number) . '.pdf');
    }
}
