<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceStoreRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Service;
use App\Models\User;
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
        $services->load(['customers','products']);
        return Inertia::render("Services/Create", ['services'=> $services]);
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
    try {

        DB::beginTransaction();

        $service = Service::create([
            'customer_id' => $request->customer_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_amount' => $request->total_amount,
            'paid_amount' => 0,
            'due_amount' => $request->total_amount,
        ]);

        // store projects
        foreach ($request->projects as $project) {

            ServiceDetail::create([
                'service_id' => $service->id,
                'product_id' => $project['product_id'],
                'project_name' => $project['project_name'] ?? null,
                'base_price' => $project['base_price'],
                'discount' => $project['discount'] ?? 0,
                'final_price' => $project['final_price'],
            ]);
        }

        // store charges
        ServiceCharge::create([
            'service_id' => $service->id,
            'installation_charge' => $request->charges['installation_charge'],
            'hosting_charge' => $request->charges['hosting_charge'],
            'server_charge' => $request->charges['server_charge'],
            'maintenance_charge' => $request->charges['maintenance_charge'],
        ]);

        DB::commit();

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('Service Store Error: ' . $e->getMessage());

        return back()->withErrors('Something went wrong. Try again.');
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
