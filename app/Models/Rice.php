<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rice extends Model
{
    use HasFactory;

    protected $primaryKey = 'rice_id';

    protected $fillable = [
        'farmer_id', 'area_type', 'seed_type',
        'area_harvested', 'production', 'ave_yield'
    ];

    protected $casts = [
        'farmer_id' => 'integer',
        'area_harvested' => 'integer',
        'production' => 'integer',
        'ave_yield' => 'integer',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }
}
