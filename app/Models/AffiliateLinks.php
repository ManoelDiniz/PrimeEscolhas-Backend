<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateLinks extends Model
{
    protected $fillable = [
        'product_id',
        'platform',
        'url',
        'price',
        'oldPrice',
        'discountPercentage',
        'currentPrice',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'oldPrice' => 'decimal:2',
        'currentPrice' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
