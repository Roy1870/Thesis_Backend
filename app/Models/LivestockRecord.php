<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockRecord extends Model
{
    use HasFactory;

    protected $table = 'livestock_records'; // Explicit table name
    protected $primaryKey = 'record_id'; // Matches migration

    public $timestamps = true; // Ensure timestamps are enabled

    protected $fillable = [
        'farmer_id',
        'animal_type',
        'subcategory',
        'quantity',
        'updated_by'
    ];

    protected $casts = [
        'record_id'  => 'integer',
        'farmer_id'  => 'integer',
        'quantity'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: LivestockRecord belongs to a Raiser.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id');
    }
}
