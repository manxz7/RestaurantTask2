<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'ingredients',
        'price',
        'image',
        'category',
        'is_available'
    ];
}