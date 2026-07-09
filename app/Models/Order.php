<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number', 
        'customer_name',
        'order_type',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'payment_status',
        'amount_paid',
        'change',
        'snap_token'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    // Satu nota order memiliki beberapa baris detail item yang dibeli
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Satu order idealnya punya satu catatan pembayaran lunas
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}