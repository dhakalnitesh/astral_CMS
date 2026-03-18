<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'commissions';
    protected $fillable = [
        'service_id',
        'agent_name',
        'commission_percentage',
        'commission_amount',
        'paid_date',
    ];

    protected $casts = [
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_date' => 'date',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}