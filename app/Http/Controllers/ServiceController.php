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
use App\Models\Payment;
use App\Models\ServiceDetail;
use App\Models\ServiceCharge;
use App\Models\ServicePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('customer:id,name','details.product')
            ->withCount('details') // number of projects
            ->when(request('search'), function ($q) {
                $q->whereHas('customer', function ($q2) {
                    $q2->where('name', 'like', '%' . request('search') . '%');
                });
            })->latest()
            ->paginate(10);
$details=ServiceDetail::all();
$details->load('product');
// dd($details);
        return Inertia::render('Services/Index', [
            'services' => $services,
            'details'=>$details,
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
     */ public function store(Request $request)
    {
        // dd($request->all());
        $service = Service::create([
            'customer_id' => $request->customer_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,

            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->due_amount,
            'total_amount' => $request->total_amount,
        ]);

        foreach ($request->projects as $p) {


            // 1. Create Service Detail (Project)
            $detail = ServiceDetail::create([
                'service_id'   => $service->id,
                'product_id'   => $p['product_id'],
                'project_name' => $p['project_name'],
                'base_price'   => $p['base_price'],
                'discount'     => $p['discount'] ?? 0,
                'final_price'  => $p['final_price'],
                'installation_charge' => $p['charges']['installation_charge'] ?? 0,
                'hosting_charge' => $p['charges']['hosting_charge'] ?? 0,
                'server_charge' => $p['charges']['server_charge'] ?? 0,
                'maintenance_charge' => $p['charges']['maintenance_charge'] ?? 0,
            ]);

            // ✅ PAYMENT PER PROJECT
            if (!empty($p['payment']) && $p['payment'] > 0) {
                ServicePayment::create([
                    'service_id' => $service->id,
                    'service_detail_id' => $detail->id,
                    'amount' => $p['payment'],
                    'payment_type' => $p['payment'] >= $p['final_price'] ? 'full' : 'partial',
                    'method' => 'cash',
                    'payment_date' => now(),
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Service::with('details.product.category')->findOrFail($id);

        return Inertia::render('Services/Show', [
            'service' => $service
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Service::with('details.product', 'charges')->findOrFail($id);

        return Inertia::render('Services/Edit', [
            'service' => $service,
            'customers' => Customer::all(),
            'products' => Product::all(),
            'categories' => Category::all(),
        ]);
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);

            $service->update([
                'customer_id' => $request->customer_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'paid_amount' => $request->paid_amount,
                'due_amount' => $request->due_amount,
                'total_amount' => $request->total_amount,
            ]);

            // delete old details first
            $service->details()->delete();
            //recreate service details

            foreach ($request->projects as $p) {
                ServiceDetail::create([
                    'service_id' => $service->id,
                    'product_id' => $p['product_id'],
                    'project_name' => $p['project_name'],
                    'base_price' => $p['base_price'],
                    'discount' => $p['discount'],
                    'final_price' => $p['final_price'],
                    'installation_charge' => $request->charges['installation_charge'],
                    'hosting_charge' => $request->charges['hosting_charge'],
                    'server_charge' => $request->charges['server_charge'],
                    'maintenance_charge' => $request->charges['maintenance_charge'],
                ]);
            }

            DB::commit();

            return redirect()->route('services.index')
                ->with('success', 'Updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors('Update failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
    }
}
