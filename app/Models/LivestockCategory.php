<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockCategory extends Model
{
    use HasFactory;

    protected $table = 'livestock_categories'; // Define table name explicitly if needed
    protected $primaryKey = 'liv_cat_id';
    protected $fillable = ['r_id', 'category_name']; // Allow mass assignment

    // Define the relationship with Raisers
    public function raiser()
    {
        return $this->belongsTo(Raiser::class, 'r_id');
    }

    // Define the relationship with Livestock Subcategories
    public function subcategories()
    {
        return $this->hasMany(LivestockSubcategory::class, 'liv_cat_id');
    }

    // Define the relationship with Livestock
    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'liv_cat_id');
    }
}
