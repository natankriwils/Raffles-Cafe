<?php

namespace App\Http\Controllers;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'stock',
        'unit',
        'min_stock',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}