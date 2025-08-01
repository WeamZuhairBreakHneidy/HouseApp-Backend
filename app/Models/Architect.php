<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Architect extends Model
{
protected $fillable = [
    'name',
    'specialization',
    'university',
    'country',
    'city',
    'experience', 
    'languages',
    'years_experience',
    'phone',
    'img_url',
];


  protected $casts = [
    'img_url' => 'array',
];

}
