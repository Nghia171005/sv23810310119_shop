<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = 'sv23810310119_products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'image_path',
        'status',
        'discount_percent',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'discount_percent' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getFinalPriceAttribute(): float
    {
        return (float) $this->price * (100 - $this->discount_percent) / 100;
    }
    protected static function booted(): void
{
    static::saving(function ($product) {
        if ((int) $product->stock_quantity === 0) {
            $product->status = 'out_of_stock';
        }
    });
}
}
