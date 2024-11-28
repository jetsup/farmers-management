<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilkDelivery extends Model
{
    protected $table = 'milk_delivery';// Specify the table name
    protected $fillable = [
        'farmer_id',
        'rate_id',
        'milk_capacity',
        'is_paid',
        'had_issues',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function breed()
    {
        return $this->belongsTo(CowBreeds::class);
    }
}
