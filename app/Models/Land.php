<?php

namespace App\Models;

use App\Models\City;
use App\Models\Distric;
use App\Models\Province;
use App\Models\Subdistric;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Land extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'prov_id','prov_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id','city_id');
    }

    public function district()
    {
        return $this->belongsTo(Distric::class, 'dis_id', 'dis_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistric::class, 'subdis_id', 'subdis_id');
    }
}
