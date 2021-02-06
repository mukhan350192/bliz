<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialEquipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'image',
        'category_id',
        'volume',
        'net',
        'mobility',
        'price',
        'price_type',
    ];
}
