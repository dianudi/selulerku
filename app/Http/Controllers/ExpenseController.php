<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function __construct()
    {
        if (! in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenses = $request->query('search') ? Expense::where('description', 'like', '%'.$request->query('search').'%')->orderBy('created_at', 'desc')->paginate(15) : Expense::orderBy('created_at', 'desc')->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $validated = $request->validated();
        $expense = new Expense($validated);
        $expense->user_id = Auth::id();
        $expense->receipt_image_path = $request->hasFile('receipt_image_path') ? $request->file('receipt_image_path')->store('receipts', 'public') : null;
        $expense->save();
        if ($request->acceptsHtml()) {
            return redirect()->route('expenses.index')->with('success', 'Expense created successfully');
        }

        return response()->json(['success' => true, 'message' => 'Expense created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        if (Auth::user()->role !== 'superadmin' && $expense->user_id !== Auth::id()) {
            return abort(403);
        }

        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        if (Auth::user()->role !== 'superadmin' && $expense->user_id !== Auth::id()) {
            return abort(403);
        }
        if ($expense->created_at < now()->subDay(2)) {
            return back()->with('error', 'Expense cannot be deleted because it is older than 2 days.');
        }
        $validated = $request->validated();
        if ($request->hasFile('receipt_image_path')) {
            if ($expense->receipt_image_path) {
                Storage::disk('public')->delete($expense->receipt_image_path);
            }
            $validated['receipt_image_path'] = $request->file('receipt_image_path')->store('receipts', 'public');
        }
        $expense->update($validated);
        if ($request->acceptsHtml()) {
            return redirect()->route('expenses.index')->with('success', 'Expense updated successfully');
        }

        return response()->json(['success' => true, 'message' => 'Expense updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if (Auth::user()->role !== 'superadmin' && $expense->user_id !== Auth::id()) {
            return abort(403);
        }
        if ($expense->created_at < now()->subDay(2)) {
            return redirect()->route('expenses.index')->with('error', 'Expense cannot be deleted because it is older than 2 days.');
        }
        if ($expense->receipt_image_path) {
            Storage::disk('public')->delete($expense->receipt_image_path);
        }
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully');
    }
}
