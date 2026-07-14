<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
    {
        protected $fillable = [
        'name',
        'slug',
        'category_id',
        'ingredient_id',
        'price',
        'stock',
        'is_instant',
        'image',
        'description',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }
}