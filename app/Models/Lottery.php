<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lottery extends Model
{
    use SoftDeletes;

    protected $table = 'lotteries';

    protected $fillable = [
        'image',
        'title',
        'description',
        'occupied_places',
        'place_count',
        'free_place_count',
        'win_promo_id',
        'is_active',
        'is_complete',
        'date_end',
    ];

    protected $casts = [
        'occupied_places' => 'array',
        'place_count' => 'integer',
        'free_place_count' => 'integer',
        'win_promo_id' => 'integer',
        'is_active' => 'boolean',
        'is_complete' => 'boolean',
        'date_end' => 'date',
    ];

    public function lotteryPromocodes(): HasMany
    {
        return $this->hasMany(LotteryPromocode::class);
    }

    public function winPromocode(): BelongsTo
    {
        return $this->belongsTo(LotteryPromocode::class,   'win_promo_id');
    }
}
