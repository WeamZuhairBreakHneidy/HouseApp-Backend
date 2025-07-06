<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
   protected $fillable = [
    'title', 'description', 'price', 
    'bedrooms', 'bathrooms', 'area', 'address'
];


}
