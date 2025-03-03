<?php

// app/Models/Farmer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $primaryKey = 'farmer_id';

    protected $fillable = ['fname','lname', 'email', 'home_address','farm_address'];

}

