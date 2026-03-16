<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['district_name','province_id'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function municipals()
    {
        return $this->hasMany(Municipal::class);
    }
}
