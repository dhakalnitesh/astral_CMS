<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_payments';
    protected $fillable = [
        'service_id',
        'amount',
        'payment_type',
        'method',
        'payment_date',
        'note',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}