<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'alternate_phone',
        'date_of_birth',
        'gender',
        'company_name',
        'company_registration_number',
        'vat_number',
        'status',
        'created_by',
        'category_id',
        'product_id',
        'province_id',
        'district_id',
        'municipal_id',
        'street',
        'tole',
        'postal_code',
        'notes',
    ];
    public function services()
    {
        return $this->hasMany(Service::class);
    }
     public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function municipal()
    {
        return $this->belongsTo(Municipal::class);
    }
    
 
}
