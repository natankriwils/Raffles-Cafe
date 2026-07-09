<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name', 
        'email', 
        'password', 
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // User terhubung ke satu Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Seorang user (kasir) bisa membuka banyak shift sepanjang waktu
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    // Seorang kasir bisa melayani banyak transaksi (orders)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
