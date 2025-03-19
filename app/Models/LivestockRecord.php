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

    protected $fillable = ['raiser_id', 'animal_type', 'subcategory', 'quantity'];

    /**
     * Relationship: LivestockRecord belongs to a Raiser.
     */
    public function raiser()
    {
        return $this->belongsTo(Raiser::class, 'raiser_id', 'raiser_id');
    }
}
