<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CowBreeds extends Model
{
    protected $fillable = [
        'breed',
    ];

    public function milks()
    {
        return $this->hasMany(MilkDelivery::class);
    }
}
