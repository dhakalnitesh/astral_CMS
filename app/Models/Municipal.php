<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipal extends Model
{
    protected $fillable = ['municipal_name','district_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}