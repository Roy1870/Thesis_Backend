<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockRecord extends Model
{
    use HasFactory;

    protected $table = 'livestock_records'; // Explicit table name
    protected $primaryKey = 'rec_id';
    protected $fillable = ['liv_id', 'sub_id', 'quantity', 'year', 'month', 'remarks']; // Allow mass assignment

    // Relationship with Livestock
    public function livestock()
    {
    }

}
