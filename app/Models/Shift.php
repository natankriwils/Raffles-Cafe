<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'user_id', 'start_time', 'end_time', 'starting_cash', 
        'ending_cash', 'difference', 'status', 'notes'
    ];

    // Shift ini dibuka oleh kasir siapa?
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Selama shift ini berjalan, ada transaksi apa saja yang masuk?
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}