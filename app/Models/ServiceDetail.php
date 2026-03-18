<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_details';
    protected $fillable = [
        'service_id',
        'product_id',
        'project_name',
        'base_price',
        'discount',
        'final_price',
    ];
    protected $casts = [
        'base_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relationship: ServiceDetail optionally belongs to a Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}