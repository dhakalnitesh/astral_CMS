<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Service;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipal;


use App\Models\Customer;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $customers = Customer::query()->with(['category','product'])
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('Customers/Index', [
        'customers' => $customers,
        'categories' => Category::select('id','name')->get(),
        'products' => Product::select('id','name','category_id')->get(),
        'filters' => $request->only('search')
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    return inertia('Customers/Create', [
        'provinces' => Province::all(),
        'districts' => District::all(),
        'municipals' => Municipal::all(),
        'categories' => Category::all(),
        'products' => Product::all(),
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request,Customer $customer)
    {
        // dd($request->all());
        $data= $request->validated();
        $customer::create($data);

        if($request->product_id){
            Service::create([
                'customer_id'=>$customer->id,
                'product_id'=>$request->product_id,
            ]);
        }
            // return redirect()->back()->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
{
    $customer->load(['province', 'district', 'municipal', 'category', 'product']);

    return Inertia::render('Customers/Show', [
        'customer' => $customer
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
 public function edit(Customer $customer)
{
    return inertia('Customers/Edit', [
        'customer' => $customer,
        'provinces' => Province::all(),
        'districts' => District::all(),
        'municipals' => Municipal::all(),
        'categories' => Category::all(),
        'products' => Product::all(),
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        // dd( $request->all());
        $data= $request->validated();
        // dd($data);
        $customer->update($data);

        return Inertia::location(route('customers.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }
}
