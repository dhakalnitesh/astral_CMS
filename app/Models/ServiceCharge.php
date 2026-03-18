<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCharge extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_charges';
    protected $fillable = [
        'service_id',
        'installation_charge',
        'hosting_charge',
        'server_charge',
        'maintenance_charge',
        'hosting_is_recurring',
        'hosting_cycle_months',
        'maintenance_is_recurring',
        'maintenance_cycle_months',
        'hosting_increase_percentage',
        'hosting_increase_after_months',
        'renewal_charge',
    ];
       protected $casts = [
        'installation_charge' => 'decimal:2',
        'hosting_charge' => 'decimal:2',
        'server_charge' => 'decimal:2',
        'maintenance_charge' => 'decimal:2',
        'hosting_is_recurring' => 'boolean',
        'hosting_cycle_months' => 'integer',
        'maintenance_is_recurring' => 'boolean',
        'maintenance_cycle_months' => 'integer',
        'hosting_increase_percentage' => 'decimal:2',
        'hosting_increase_after_months' => 'integer',
        'renewal_charge' => 'decimal:2',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}