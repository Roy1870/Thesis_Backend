<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockSubcategory extends Model
{
    use HasFactory;

    protected $table = 'livestock_subcategories'; // Explicit table name
    protected $primaryKey = 'sub_id';
    protected $fillable = ['liv_cat_id', 'sub_name']; // Allow mass assignment

    // Define the relationship with Livestock Category
    public function livestockCategory()
    {
        return $this->belongsTo(LivestockCategory::class, 'liv_cat_id');
    }

    // Define the relationship with Livestock
    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'sub_id');
    }
}

