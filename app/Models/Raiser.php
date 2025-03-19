<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raiser extends Model
{
    use HasFactory;

    protected $table = 'raisers'; // Explicitly define table name
    protected $primaryKey = 'raiser_id';
    public $timestamps = true;

    protected $fillable = ['farmer_id', 'location', 'updated_by', 'remarks'];

    /**
     * Define attribute casting to ensure proper data types.
     */
    protected $casts = [
        'farmer_id' => 'integer',
        'raiser_id' => 'integer',
    ];

    /**
     * Define relationship: Raiser belongs to a Farmer.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id');
    }
}
