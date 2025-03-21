<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raiser extends Model
{
    use HasFactory;

    protected $primaryKey = 'raiser_id';

    protected $fillable = [
        'farmer_id',
        'location',
        'updated_by',
        'remarks'
    ];

    /**
     * Automatically cast attributes to appropriate data types.
     */
    protected $casts = [
        'raiser_id' => 'integer',
        'farmer_id' => 'integer',
        'location' => 'string',
        'updated_by' => 'string',
        'remarks' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship: A raiser belongs to a farmer.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id');
    }

    /**
     * Relationship: A raiser has many livestock records.
     */
    public function livestockRecords()
    {
        return $this->hasMany(LivestockRecord::class, 'raiser_id', 'raiser_id');
    }
}


