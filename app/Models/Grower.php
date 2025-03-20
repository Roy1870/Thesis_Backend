<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grower extends Model
{
    use HasFactory;

    protected $primaryKey = 'grower_id'; // Custom primary key

    protected $fillable = ['farmer_id'];

    protected $casts = [
        'grower_id' => 'integer',
        'farmer_id' => 'integer'
    ];

    public function crops()
    {
        return $this->hasMany(Crops::class, 'grower_id');
    }

    public function rice()
    {
        return $this->hasMany(Rice::class, 'grower_id');
    }
}


