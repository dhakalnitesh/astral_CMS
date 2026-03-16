<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',       // stores category id
        'name',
        'price',
        'code',
        'description',
        'project_lead',      // stores employee id
        'total_developers',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relationship: Product belongs to a category
     */
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
    public function projectLead()
    {
        return $this->belongsTo(Employee::class, 'project_lead')->withDefault();
    }
    // This way, if a product has no project lead or category, $product->projectLead->name won’t throw an error.If we use the withDefault() then.
}