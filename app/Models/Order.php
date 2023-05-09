<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'number',
        'name',
        'phone',
        'address',
        'text',
        'status',
        'delivery_price',
        'changed_summary_price',
        'changed_delivery_price',
        'delivery_range',
        'products',
    ];

    protected $casts = [
        'delivery_price'=>'integer',
        'changed_summary_price'=>'integer',
        'changed_delivery_price'=>'integer',
        'delivery_range'=>'integer',
        'products'=>'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $enums = [
        'status' => OrderStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
