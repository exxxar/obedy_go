<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'description',
        'day_index',
        'image',
        'price',
        'weight',
        'category_id',
        'food_part_id',
        'positions',
        'is_week',
        'disabled',
        'addition'

    ];

    protected $casts = [
        'positions' => 'array',
        'day_index'=>'integer',
        'price'=>'double',
        'weight'=>'double',
        'is_week'=>'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
