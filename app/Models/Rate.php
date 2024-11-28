<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['breed_id', 'rate'];

    public function breed()
    {
        return $this->belongsTo(CowBreeds::class, 'breed_id');
    }
}
