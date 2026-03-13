<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'service_id',
        'payment_type',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_date'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}