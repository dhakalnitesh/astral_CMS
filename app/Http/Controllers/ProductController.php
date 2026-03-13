<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

  public function index(Request $request)
{
    $search = $request->search;

    $products = Product::query()
        ->when($search, function ($query, $search) {
            $query->where('name','like',"%{$search}%")
                  ->orWhere('description','like',"%{$search}%")
                  ->orWhere('price','like',"%{$search}%");
        })
        ->latest()
        ->paginate(10)->withQueryString();

    $categories = Category::select('id','name')->get();

    return Inertia::render('Products/Index', [
        'products' => $products,
        'filters' => [
            'search' => $search
        ],
        'categories' => $categories
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        return Inertia::render("Products/Create", ["products" => $products, "categories" => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // dd($request->all());
        $product = Product::create($request->validated());
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return Inertia::render('Products/Edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request,Product $product)
    {
         $product->update($request->validated());
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Product $product)
    {
        $product->delete($product);
    }
}
