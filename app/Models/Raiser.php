<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raiser extends Model
{
    use HasFactory;

    protected $table = 'raisers'; // Explicitly define table name (optional)
    protected $primaryKey = 'r_id';
    public $timestamps = true; // Ensure timestamps are managed

    protected $fillable = ['species', 'remarks', 'farmer_id'];

    /**
     * Define relationship: Raiser belongs to a Farmer.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id');
    }
}
