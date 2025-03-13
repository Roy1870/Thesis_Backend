<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestock extends Model
{
    use HasFactory;

    protected $table = 'livestock'; // Explicit table name
    protected $primaryKey = 'liv_id';
    protected $fillable = ['liv_cat_id', 'sub_id', 'rec_id', 'r_id']; // Allow mass assignment

    // Relationship with LivestockCategory
    public function category()
    {
        return $this->belongsTo(LivestockCategory::class, 'liv_cat_id');
    }

    // Relationship with LivestockSubcategory
    public function subcategory()
    {
        return $this->belongsTo(LivestockSubcategory::class, 'sub_id');
    }

    // Relationship with LivestockRecord
    public function record()
    {
        return $this->belongsTo(LivestockRecord::class, 'rec_id');
    }

    // Relationship with Raisers
    public function raiser()
    {
        return $this->belongsTo(Raiser::class, 'r_id');
    }
}
