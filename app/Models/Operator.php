<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $table = 'operators'; // Explicit table name
    protected $primaryKey = 'operator_id'; // Matches migration

    public $timestamps = true; // Ensure timestamps are enabled

    protected $fillable = [
        'farmer_id',
        'fishpond_location',
        'cultured_species',
        'productive_area_sqm',
        'stocking_density',
        'date_of_stocking',
        'production_kg',
        'date_of_harvest',
        'remarks',
        'created_at',
        'updated_at'
    ];

    /**
     * Ensure numeric fields are cast correctly
     */
    protected $casts = [
        'farmer_id' => 'integer',
        'operator_id' => 'integer',
        'productive_area_sqm' => 'float',
        'stocking_density' => 'float',
        'production_kg' => 'float',
    ];

    public function farmer()
{
    return $this->belongsTo(Farmer::class, 'farmer_id');
}

}
