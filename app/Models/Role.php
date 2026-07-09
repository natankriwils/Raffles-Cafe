<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name'];

    // Satu role bisa dimiliki oleh banyak user (Kasir bisa banyak orang)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}