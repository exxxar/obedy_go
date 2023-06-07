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
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasRoles;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'addresses',
        'password',
        'image',
        'description'
        /*'trusted_count',
        'trusted_limit',
        'is_trusted',
        'active',
        'telegram_chat_id',*/
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
        return $this->belongsToMany(Product::class, 'carts')->withPivot('users');
    }

    public function inCart($product)
    {
        return $this->products()->where('products.id', $product['product']['id'])->exists();
    }


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }


    public function purchasedMenus(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'menu_user');
    }
}
