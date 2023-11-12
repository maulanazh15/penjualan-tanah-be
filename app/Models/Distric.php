<?php

namespace App\Models;

use App\Models\Subdistric;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distric extends Model
{
    use HasFactory;

    protected $primaryKey = 'dis_id';
    protected $table = 'districts';

    public function subdistricts()
    {
        return $this->hasMany(Subdistric::class, 'dis_id', 'dis_id');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }


}
