<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropYield extends Model
{
    use HasFactory;

    protected $table = 'crop_yields'; // Adjust if needed
    protected $primaryKey = 'c_id';
    public $timestamps = true;

    protected $fillable = [
        'g_id', 'area', 'variety',
        'jan_cnt', 'feb_cnt', 'march_cnt', 'april_cnt', 'may_cnt', 'june_cnt',
        'july_cnt', 'aug_cnt', 'sept_cnt', 'oct_cnt', 'nov_cnt', 'dec_cnt','remarks'
    ];

    public function grower()
    {
        return $this->belongsTo(Grower::class, 'g_id');
    }
}
