<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $table = 'crops'; // Explicit table name
    protected $primaryKey = 'crop_id'; // Matches migration

    public $timestamps = true; // Ensure timestamps are enabled

    protected $fillable = [
        'grower_id', 
        'crop_type', 
        'variety_clone', 
        'area_hectare', 
        'production_type', 
        'production_data'
    ];

    protected $casts = [
        'crop_id'         => 'integer',
        'grower_id'       => 'integer',
        'area_hectare'    => 'float',
        'production_data' => 'array', // Ensure production data is always cast as an array (JSON stored in DB)
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    /**
     * Relationship: Crop belongs to a Grower.
     */
    public function grower()
    {
        return $this->belongsTo(Grower::class, 'grower_id', 'grower_id');
    }
}
