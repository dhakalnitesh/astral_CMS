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
    public function index()
    {
        
        $products = Product::all();
        return Inertia::render("Products/Index",["products"=> $products]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all(); 
        $categories= Category::all();
        return Inertia::render("Products/Create",["products"=> $products,"categories"=> $categories]);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
