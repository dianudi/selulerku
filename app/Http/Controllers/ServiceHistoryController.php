<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceHistoryRequest;
use App\Http\Requests\UpdateServiceHistoryRequest;
use App\Models\ServiceDetail;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;

class ServiceHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $serviceHistories = $request->has('search') ? ServiceHistory::where('name', 'like', '%' . $request->search . '%')->paginate(10) : ServiceHistory::paginate(10);
        return view('serviceHistories.index', compact('serviceHistories'));
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
            'customer_id' => $data['customer_id'],
            'invoice_number' => $data['invoice_number'],
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

        return redirect()->route('servicehistories.index')->with('success', 'Service History created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceHistory $serviceHistory)
    {
        return view('serviceHistories.show', compact('serviceHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceHistory $serviceHistory)
    {
        return view('serviceHistories.edit', compact('serviceHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceHistoryRequest $request, ServiceHistory $serviceHistory)
    {
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
        return redirect()->route('servicehistories.index')->with('success', 'Service History updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceHistory $serviceHistory)
    {
        $serviceHistory->details()->delete();
        $serviceHistory->delete();
        return redirect()->route('servicehistories.index')->with('success', 'Service History deleted successfully');
    }
}
