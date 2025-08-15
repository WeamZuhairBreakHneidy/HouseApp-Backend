<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Other extends Model
{
    protected $fillable = [
        'category', 'title', 'location', 'address', 'img_url', 'description', 'price',
        'main_features', 'area_distance', 'arealength', 'areawidth', 'floors_number',
        'is_rent', 'is_sell'
    ];

    protected $casts = [
        'main_features' => 'array',
        'is_rent' => 'boolean',
        'is_sell' => 'boolean',
        'img_url' => 'array',
    ];
}
