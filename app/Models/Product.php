<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'code',
        'price',
        'project_lead',      
        'description',
        'category_id',      
        'total_developers',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withDefault();
    }

    /**
     * Relationship: Product has many services
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'product_id');
    }

    /**
     * Relationship: Product belongs to a project lead (Employee)
     */
    public function employees()
    {
        return $this->belongsTo(Employee::class,'project_lead');
    }
    // This way, if a product has no project lead or category, $product->projectLead->name won’t throw an error.If we use the withDefault() then.
}