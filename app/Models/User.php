<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'telegram_chat_id',
        'addresses',
        'active',
        'password',
        'trusted_count',
        'trusted_limit',
        'is_trusted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'addresses'=>'array'
    ];

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'carts')->withPivot('quantity', 'name', 'phone');
    }


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
