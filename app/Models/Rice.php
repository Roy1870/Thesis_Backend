<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rice extends Model
{
    use HasFactory;

    protected $primaryKey = 'rice_id';

    protected $fillable = [
        'grower_id', 'area_type', 'seed_type',
        'area_harvested', 'production', 'ave_yield'
    ];

    protected $casts = [
        'grower_id' => 'integer',
        'area_harvested' => 'integer',
        'production' => 'integer',
        'ave_yield' => 'integer',
    ];

    public function grower()
    {
        return $this->belongsTo(Grower::class, 'grower_id');
    }
}
