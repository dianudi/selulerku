<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::with(['orders', 'serviceHistories'])->latest();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $customers = $query->paginate(12);

        if ($request->acceptsHtml()) {
            return view('customers.index', compact('customers'));
        }

        // note: for ajax request
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);
        $validated['user_id'] = Auth::id();
        $customer = Customer::create($validated);
        if ($request->acceptsHtml()) {
            return redirect()->route('customers.index')->with('success', 'Customer created successfully');
        }

        return response()->json(['message' => 'Customer created successfully.', 'customer' => $customer]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load(['orders.details', 'serviceHistories.details']);

        $totalOrder = $customer->orders->reduce(function ($carry, $order) {
            return $carry + $order->details->sum('immutable_price');
        }, 0);

        $totalService = $customer->serviceHistories->reduce(function ($carry, $service) {
            return $carry + $service->details->sum('price');
        }, 0);

        return view('customers.show', compact('customer', 'totalOrder', 'totalService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);
        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->orders()->exists() || $customer->serviceHistories()->count() > 0) {
            return redirect()->route('customers.index')->with('error', 'Customer cannot be deleted because it has associated orders.');
        }
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }
}
