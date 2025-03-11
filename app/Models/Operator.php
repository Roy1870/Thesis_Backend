<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $primaryKey = 'o_id';

    protected $fillable = ['fishpong_location','cultured_species', 'productive_area', 'stocking_density','production','harvest_date','month','year','farmer_id'];

    public function farmer()
    {
        return $this->hasMany(Farmer::class, 'farmer_id', 'farmer_id');
    }
}
