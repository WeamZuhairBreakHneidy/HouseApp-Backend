<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class House extends Model
{
    protected $fillable = [
        'title', 'location', 'address', 'img_url', 'description', 'price',
        'rooms_number', 'baths_number', 'floors_number', 'ground_distance',
        'building_age', 'main_features', 'is_furnitured', 'is_rent', 'is_sell'
    ];

    protected $casts = [
        'main_features' => 'array',
        'is_furnitured' => 'boolean',
        'is_rent' => 'boolean',
        'is_sell' => 'boolean',
    ];
}
