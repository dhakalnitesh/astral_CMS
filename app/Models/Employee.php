<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'designation',
        'department',
        'joining_date',
        'end_date',
        'salary',
        'experience_years',
        'province_id',
        'district_id',
        'municipal_id',
        'street',
        'tole',
    ];

    public function province()
    {
        return $this->belongsTo(\App\Models\Province::class);
    }

    public function district()
    {
        return $this->belongsTo(\App\Models\District::class);
    }

    public function municipal()
    {
        return $this->belongsTo(\App\Models\Municipal::class);
    }
}
