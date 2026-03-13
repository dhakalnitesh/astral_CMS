<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'user_id',
        'company_name',
        'phone',
        'address',
        'notes',
        'status',
    ];
public function services()
{
    return $this->hasMany(Product::class);
}
}
