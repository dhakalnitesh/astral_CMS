<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'service_code',
        'start_date',
        'end_date',
        'status',
        'total_amount',
        'paid_amount',
        'due_amount',
        'note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    // public function products()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function details()
    {
        return $this->hasMany(ServiceDetail::class);
    }

    /**
     * Service charges (installation, hosting, maintenance, etc.)
     */
    public function charges()
    {
        return $this->hasOne(ServiceCharge::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}