<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->search;

    $categories = Category::query()
        ->when($search, function ($query, $search) {
            $query->where('name','like',"%{$search}%")
                  ->orWhere('description','like',"%{$search}%");
        })
        ->latest()
        ->get();

    return Inertia::render('Categories/Index', [
        'categories' => $categories,
        'filters' => [
            'search' => $search
        ]
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryRequest $request)
    {
            Category::create($request->validated());

    return redirect()->route('categories.index');
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
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete($category);
    }
}
