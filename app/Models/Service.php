<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'service_start_date',
        'service_end_date',
        'tenure_months',
        'price',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
