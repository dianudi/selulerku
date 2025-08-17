<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // todo : retrieve all if user is super admin
        $orders = $request->has('search') ? Order::where('invoice_number', 'like', '%' . $request->search . '%')->where('user_id', Auth::id())->paginate(15) : Order::where('user_id', Auth::id())->paginate(15);
        return view('orders.index', compact('orders'));
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
                        'immutable_price' => $product->price
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
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update([
            'status' => $request->input('status')
        ]);
        $order->details()->delete();
        $order->details()->createMany(
            collect($request->input('details'))->map(function ($detail) {
                return [
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'immutable_price' => Product::select('price')->where('id', $detail['product_id'])->first()->price
                ];
            })
        );
        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // todo: add check days
        // if (now()->diffInDays($order->created_at) < 1) return redirect()->route('orders.index')->with('error', 'Order cannot be deleted because it was paid after 1 day of creation.');
        $order->details()->delete();
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
}
