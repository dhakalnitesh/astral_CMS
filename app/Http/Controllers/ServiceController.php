<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceStoreRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Service;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\ServiceDetail;
use App\Models\ServiceCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = Service::with('customer','products')->paginate(10);

        return Inertia::render('Services/Index', [
            'services' => $services,
            'filters' => request()->only(['search', 'perPage']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        $products = Product::all();
        $customers = Customer::all();
        $categories = Category::all();

        // $services->load(['customers','products']);
        return Inertia::render("Services/Create", ['services' => $services, 'products' => $products, 'customers' => $customers, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */public function store(Request $request)
{
    $service = Service::create([
        'customer_id' => $request->customer_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
    ]);

    foreach ($request->projects as $project) {

        // 1. Create Service Detail (Project)
        $detail = ServiceDetail::create([
            'service_id'   => $service->id,
            'product_id'   => $project['product_id'],
            'project_name' => $project['project_name'],
            'base_price'   => $project['base_price'],
            'discount'     => $project['discount'] ?? 0,
            'final_price'  => $project['final_price'],
        ]);

        // 2. Create Charges FOR THIS PROJECT
        if (isset($project['charges'])) {
            ServiceCharge::create([
                'service_id' => $service->id,
                // optional: better to link with service_detail_id
                // 'service_detail_id' => $detail->id,

                'hosting_charge'      => $project['charges']['hosting_charge'] ?? 0,
                'installation_charge' => $project['charges']['installation_charge'] ?? 0,
                'server_charge'       => $project['charges']['server_charge'] ?? 0,
                'maintenance_charge'  => $project['charges']['maintenance_charge'] ?? 0,
            ]);
        }
    }
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
    public function edit( $id)
    {
        $services=Service::with(['charges'])->find($id);
        // dd($services);
        // $services->load(['products,category','customer']);
        $products=Product::all();
        $products->load(['customer','category']);
// dd($products);
$serCharge=ServiceCharge::all();
dd(@$serCharge->all());
        // $products = Product::all();
        $customers = Customer::all();
        $categories = Category::all();

        return Inertia::render("Services/Edit", ['services' => $services]);
    }
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
    }
}
