<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Employee;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $query = Employee::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'filters' => [
                'search' => $search,
                'perPage' => $perPage
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Employees/Create', [
            'provinces' => \App\Models\Province::all(),
            'districts' => \App\Models\District::all(),
            'municipals' => \App\Models\Municipal::all(),
        ]);
    }

    public function store(EmployeeStoreRequest $request)
    {
        Employee::create($request->validated());
        return back()->with([
            'success' => 'Employee created successfully!',
            'refresh_index' => true
        ]);
    }

    public function show(Employee $employee)
    {
        return Inertia::render('Employees/Show', ['employee' => $employee]);
    }

    public function edit(Employee $employee)
    {
        return Inertia::render('Employees/Edit', [
            'employee' => $employee,
            'provinces' => \App\Models\Province::all(),
            'districts' => \App\Models\District::all(),
            'municipals' => \App\Models\Municipal::all(),
        ]);
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index');
    }
}
