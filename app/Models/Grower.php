<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grower extends Model
{
    use HasFactory;

    protected $primaryKey = 'g_id';

    protected $fillable = ['crop_name','area_hectares', 'yield', 'season','market_outlet','farmer_id'];

    public function farmer()
    {
        return $this->hasMany(Farmer::class, 'farmer_id', 'farmer_id');
    }
}
