<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedHouse extends Model
{
    protected $fillable = [
        'house_id',
    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
