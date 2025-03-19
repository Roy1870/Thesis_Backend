<?php

// app/Models/Farmer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $primaryKey = 'farmer_id';

    protected $fillable = ['name', 'contact_number','facebook_email', 'home_address','farm_address','farm_location_longitude','farm_location_latitude','market_outlet_location','buyer_name','association_organization'];

}

