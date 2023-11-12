<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistric extends Model
{
    use HasFactory;

    protected $primaryKey = 'subdis_id';
    protected $table = 'subdistricts';
 
    public function district() {
        return $this->belongsTo(Distric::class, 'dis_id', 'dis_id');
    }
}
