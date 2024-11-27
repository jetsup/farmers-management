<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'location',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
