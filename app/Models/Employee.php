<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'employee_code',
        'designation',
        'department',
        'role',
        'joining_date',
        'end_date',
        'salary',
        'experience_years',
        'address',
        'city',
        'state',
        'country',
        'notes',
    ];

    // Optional: Full name accessor
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'employee_product', 'employee_id', 'product_id')->withPivot('assigned_date', 'role');
    }
}
