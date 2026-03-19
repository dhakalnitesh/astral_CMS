<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceStoreRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Service;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
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
        $services=Service::all();
        $products=Product::all();
        $customers=Customer::all();

        $services->load(['customers','products']);
        return Inertia::render("Services/Create", ['services'=> $services,'products'=> $products,'customers'=> $customers]);
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
    public function store(ServiceStoreRequest $request)
{
  dd($request->validated());
  $service = Service::create([
    'customer_id'=> $request->customer_id,
    'start_date'=> $request->start_date,
    'end_date'=>$request->end_date,
  ]);
  dd($service->all());
  foreach($request->projects as $project)
  {
    ServiceDetail::create([
        'service_id'=>$service->id,
        'product_id'=> $project['product_id'],
        'project_name'=> $project['project_name'],
        'base_price'=> $project['base_price'],
        'discount' => $project['discount'] ?? 0,
        'final_price' => $project['final_price'],
    ]);
  }
foreach($request->charges as $charge)
    {

        ServiceCharge::create([
          'hosting_charge'=>$charge['hosting_charge']?? null,
          'installation_charge'=>$charge['installation_charge']?? null,
          'server_charge'=> $charge['server_charge']?? null,
          'maintenance_charge'=> $charge['maintenance_charge']?? null,
        ]);
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
