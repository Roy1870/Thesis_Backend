<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grower extends Model
{
    use HasFactory;

    protected $primaryKey = 'grower_id'; // Updated to match your schema

    protected $fillable = ['farmer_id', 'created_at', 'updated_at']; // Adjust based on actual table

    protected $casts = [
        'grower_id'  => 'integer',
        'farmer_id'  => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id'); // A grower belongs to a farmer
    }
}
