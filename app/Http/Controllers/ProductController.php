<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productCount = Product::count();
        $productSoldCountToday = Product::whereHas('orderDetails')->where('created_at', '>=', now()->startOfDay())->count();
        $productSoldCountYesterday = Product::whereHas('orderDetails')->where('created_at', '>=', now()->startOfDay()->subDay())->count();
        $productProfitToday = Product::whereHas('orderDetails', function ($query) {
            $query->where('created_at', '>=', now()->startOfDay());
        })->with(['orderDetails' => function ($query) {
            $query->where('created_at', '>=', now()->startOfDay());
        }])->get()->sum(function ($product) {
            return $product->orderDetails->sum('immutable_price');
        });
        $productProfitYesterday = Product::whereHas('orderDetails', function ($query) {
            $query->where('created_at', '>=', now()->startOfDay()->subDay())
                ->where('created_at', '<', now()->startOfDay());
        })->with(['orderDetails' => function ($query) {
            $query->where('created_at', '>=', now()->startOfDay()->subDay())
                ->where('created_at', '<', now()->startOfDay());
        }])->get()->sum(function ($product) {
            return $product->orderDetails->sum('immutable_price');
        });
        $lowStockCount = Product::where('quantity', '<', 5)->count();
        $products = $request->has('search') ? Product::where('name', 'like', '%'.$request->search.'%')->orderBy('name', 'asc')->paginate(15) : Product::orderBy('name', 'asc')->paginate(15);

        return view('products.index', compact('products', 'productCount', 'productSoldCountToday', 'productSoldCountYesterday', 'productProfitToday', 'productProfitYesterday', 'lowStockCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return abort(403);
        }

        $categories = ProductCategory::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        if (! in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return abort(403);
        }

        $product = new Product($request->validated());
        $product->user_id = Auth::id();
        $product->image = $request->has('image') ? $request->image->store('products', 'public') : null;
        $product->save();
        if ($request->acceptsHtml()) {
            return redirect()->route('products.index')->with('success', 'Product created successfully');
        }

        return response()->json(['message' => 'Product created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // as show too
        // if (!in_array(Auth::user()->role, ['admin', 'superadmin'])) return abort(403);

        $categories = ProductCategory::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if (! in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return abort(403);
        }

        $data = $request->validated();
        if ($request->has('image')) {
            $data['image'] = $request->image->store('products', 'public');
        }
        $product->update($data);
        if ($request->acceptsHtml()) {
            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        }

        return response()->json(['message' => 'Product updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (! in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return abort(403);
        }

        if ($product->orderDetails()->exists()) {
            return redirect()->route('products.index')->with('error', 'Product has order history. Cannot delete.');
        }
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
