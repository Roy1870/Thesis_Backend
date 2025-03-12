<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raiser extends Model
{
    use HasFactory;

    protected $primaryKey = 'r_id';

    protected $fillable = ['species','remarks','farmer_id'];

    public function farmer()
    {
        return $this->hasMany(Farmer::class, 'farmer_id', 'farmer_id');
    }

}
