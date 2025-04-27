<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $primaryKey = 'farmer_id';

    protected $fillable = [
        'name',
        'contact_number',
        'facebook_email',
        'rsbsa_id',
        'home_address',
        'farm_address',
        'farm_location_longitude',
        'farm_location_latitude',
        'market_outlet_location',
        'buyer_name',
        'association_organization',
        'barangay'
    ];

    protected $casts = [
        'farmer_id'               => 'integer',
        'farm_location_longitude' => 'float',
        'farm_location_latitude'  => 'float',
        'created_at'              => 'datetime',
        'updated_at'              => 'datetime',
    ];

    // Define the crops relationship
    public function crops()
    {
        return $this->hasMany(Crops::class, 'farmer_id');
    }

    // Define the rice relationship
    public function rice()
    {
        return $this->hasMany(Rice::class, 'farmer_id');
    }

    // Define the livestock records relationship
    public function livestockRecords()
    {
        return $this->hasMany(LivestockRecord::class, 'farmer_id');
    }

    public function operators()
{
    return $this->hasMany(Operator::class, 'farmer_id');
}

}
