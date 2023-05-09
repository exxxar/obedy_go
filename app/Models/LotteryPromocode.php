<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LotteryPromocode extends Model
{
    protected $table = 'lottery_promocodes';

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'max_activation_count',
        'current_activation_count',
        'lottery_id'
    ];

    protected $appends = [
        'is_activated'
    ];

    public function getIsActivatedAttribute()
    {
        return $this->current_activation_count == $this->max_activation_count;
    }

    public function lottery(): BelongsTo
    {
        return $this->belongsTo(Lottery::class);
    }
}
