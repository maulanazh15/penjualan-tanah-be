<?php

namespace App\Models;

use App\Models\Distric;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $primaryKey = 'city_id';

    public function districts()
    {
        return $this->hasMany(Distric::class, 'city_id', 'city_id');
    }

    public function province() {
        return $this->belongsTo(Province::class, 'prov_id', 'prov_id');
    }
}
