<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Employee;


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
            ->with(['category','employees'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)->withQueryString();

        $categories = Category::select('id', 'name')->get();
        $employees = Employee::select('id','full_name')->get();
// dd($employees->all());

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => [
                'search' => $search
            ],
            'categories' => $categories,
            'employees'=>$employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch categories for the category dropdown
        $categories = Category::select('id', 'name')->get();

        // Fetch employees for the project lead dropdown
        $employees = Employee::select('id', 'name')->get();

        // Pass them to Inertia
        return Inertia::render('Products/Create', [
            'categories' => $categories,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // dd($request->all());
        // $product = Product::create($request->validated());
        // return redirect()->route('products.index');

        $data = $request->validated();
        // Handle new category
        if (!empty($data['new_category'])) {
            $category = Category::create([
                'name' => $data['new_category']
            ]);
            // dd($category->all());
            // Replace category_id with new category's id
            $data['category_id'] = $category->id;
        }
        // Remove new_category from data before saving product
        unset($data['new_category']);
        // dd($data);
        Product::create($data);

        return back()->with([
            'success' => 'Product created successfully!',
            'refresh_index' => true
        ]);    }

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
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return back()->with([
            'success' => 'Product updated successfully!',
            'refresh_index' => true
        ]);  
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete($product);
    }
}
