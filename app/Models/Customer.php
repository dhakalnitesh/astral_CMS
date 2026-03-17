<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'date_of_birth',
        'gender',
        'user_id',
        'company_name',
        'phone',
        'notes',
        'status',
        'category_id',
        'product_id',
        'province_id',
        'district_id',
        'municipal_id',
        'street',
        'tole',
    ];
public function services()
{
    return $this->hasMany(Service::class);
}
}
