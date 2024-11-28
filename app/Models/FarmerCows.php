<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerCows extends Model
{
    protected $table = 'farmer_cows';
    protected $fillable = [
        'farmer_id',
        'breed_id',
        'name',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function breed()
    {
        return $this->belongsTo(CowBreeds::class, 'breed_id');
    }
}
