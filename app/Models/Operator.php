<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $primaryKey = 'operator_id'; // Updated to match schema

    protected $fillable = [
        'farmer_id',
        'fishpond_location',
        'geotagged_photo_url',
        'cultured_species',
        'productive_area_sqm',
        'stocking_density',
        'date_of_stocking',
        'production_kg',
        'date_of_harvest',
        'operational_status',
        'remarks',
        'created_at',
        'updated_at'
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id'); // A grower belongs to a farmer
    }
}
