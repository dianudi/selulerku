<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Gate;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        if (Gate::denies('superadmin') && Gate::denies('admin')) {
            return abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productCategories = ProductCategory::all();

        return view('productCategories.index', compact('productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        $productCategory = new ProductCategory($request->validated());
        $productCategory->icon = $request->has('icon') ? $request->file('icon')->store('productCategories', 'public') : null;
        $productCategory->save();
        if ($request->acceptsJson()) {
            return response()->json(['message' => 'Product category created successfully.']);
        }

        return redirect()->route('productcategories.index')->with('success', 'Product category created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $productCategory->name = $request->input('name');
        $productCategory->icon = $request->has('icon') ? $request->file('icon')->store('productCategories', 'public') : null;
        $productCategory->save();
        if ($request->acceptsJson()) {
            return response()->json(['message' => 'Product category updated successfully.']);
        }

        return redirect()->route('productcategories.index')->with('success', 'Product category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->count() > 0) {
            return redirect()->route('productcategories.index')->with('error', 'Product category cannot be deleted because it has associated products.');
        }
        $productCategory->delete();

        return redirect()->route('productcategories.index')->with('success', 'Product category deleted successfully.');
    }
}
