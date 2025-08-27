<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceHistoryRequest;
use App\Http\Requests\UpdateServiceHistoryRequest;
use App\Models\ServiceDetail;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // todo: admin can see all except for admin or cashier
        $serviceHistories = ServiceHistory::with('customer', 'details');

        if ($request->has('search')) {
            $serviceHistories->whereHas('customer', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('invoice_number', 'like', '%' . $request->search . '%');
        }

        $serviceHistories = $serviceHistories->paginate(10);

        $pendingCount = ServiceHistory::where('status', 'pending')->count();
        $onProcessCount = ServiceHistory::where('status', 'on_process')->count();
        $doneCount = ServiceHistory::where('status', 'done')->count();

        return view('serviceHistories.index', compact('serviceHistories', 'pendingCount', 'onProcessCount', 'doneCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('serviceHistories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceHistoryRequest $request)
    {
        $data = $request->validated();
        $serviceHistory = new ServiceHistory([
            'user_id' => Auth::user()->id,
            'customer_id' => $data['customer_id'],
            'invoice_number' => 'INV-' . $data['customer_id'] . '-' . now()->format('Y/m/d') . '-' . rand(1000, 9999) . '-' . rand(1000, 9999),
            'warranty_expired_at' => $data['warranty_expired_at'],
            'status' => $data['status']
        ]);
        $serviceHistory->save();

        $serviceHistory->details()->createMany(collect($data['details'])->map(function ($detail) {
            return [
                'kind' => $detail['kind'],
                'description' => $detail['description'],
                'price' => $detail['price']
            ];
        })->toArray());

        // if ($request->acceptsHtml()) return redirect()->route('servicehistories.index')->with('success', 'Service History created successfully');
        return response()->json(['message' => 'Service History created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceHistory $serviceHistory)
    {
        if ($serviceHistory->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return redirect()->route('servicehistories.index')->with('error', 'Unauthorized');

        return view('serviceHistories.show', compact('serviceHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceHistory $serviceHistory)
    {
        if ($serviceHistory->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return redirect()->route('servicehistories.index')->with('error', 'Unauthorized');

        return view('serviceHistories.edit', compact('serviceHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceHistoryRequest $request, ServiceHistory $serviceHistory)
    {
        if ($serviceHistory->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return redirect()->route('servicehistories.index')->with('error', 'Unauthorized');

        $data = $request->validated();
        $serviceHistory->update([
            'total_revision' => $data['total_revision'],
            'status' => $data['status']
        ]);
        $serviceHistory->details()->delete();
        $serviceHistory->details()->createMany(collect($data['details'])->map(function ($detail) {
            return [
                'kind' => $detail['kind'],
                'description' => $detail['description'],
                'price' => $detail['price']
            ];
        })->toArray());
        return response()->json(['message' => 'Service History updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceHistory $serviceHistory)
    {
        if ($serviceHistory->user_id !== Auth::user()->id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) return redirect()->route('servicehistories.index')->with('error', 'Unauthorized');
        $serviceHistory->details()->delete();
        $serviceHistory->delete();
        return redirect()->route('servicehistories.index')->with('success', 'Service History deleted successfully');
    }
}
