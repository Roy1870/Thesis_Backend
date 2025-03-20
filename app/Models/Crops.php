<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crops extends Model
{
    use HasFactory;

    protected $primaryKey = 'crop_id';

    protected $fillable = [
        'grower_id', 'crop_type', 'variety_clone', 'area_hectare',
        'production_type', 'production_data'
    ];

    protected $casts = [
        'grower_id' => 'integer',
        'area_hectare' => 'float',
        'production_data' => 'array' // Ensures JSON field is automatically cast to an array
    ];

    public function grower()
    {
        return $this->belongsTo(Grower::class, 'grower_id');
    }
}
