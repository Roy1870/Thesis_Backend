<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crops extends Model
{
    use HasFactory;

    protected $primaryKey = 'crop_id';

    protected $fillable = [
        'farmer_id', 'crop_type', 'variety_clone', 'area_hectare',
        'production_type', 'production_data'
    ];

    protected $casts = [
        'farmer_id' => 'integer',
        'area_hectare' => 'float',
        'production_data' => 'array'
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }
}
