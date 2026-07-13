<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variant extends Model
{
    protected $fillable = [
        'product_id', 
        'name', 
        'additional_price', 
        'is_available'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}