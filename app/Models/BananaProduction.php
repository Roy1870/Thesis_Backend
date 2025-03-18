<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BananaProduction extends Model
{
    use HasFactory;

    protected $table = 'banana_productions'; // Adjust if needed
    protected $primaryKey = 'b_id';
    public $timestamps = true;

    protected $fillable = [
        'g_id', 'area', 'lakatan_cnt', 'cardava_cnt', 'month', 'year', 'remarks'
    ];

    public function grower()
    {
        return $this->belongsTo(Grower::class, 'g_id');
    }
}
