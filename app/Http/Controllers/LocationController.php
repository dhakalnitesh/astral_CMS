<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Province;
use App\Models\District;
use App\Models\Municipal;

class LocationController extends Controller
{
    public function provinces()
    {
        return Province::all();
    }

    public function districts($province_id)
    {
        return District::where('province_id', $province_id)->get();
    }

    public function municipals($district_id)
    {
        return Municipal::where('district_id', $district_id)->get();
    }
}