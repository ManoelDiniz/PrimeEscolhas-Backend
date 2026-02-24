<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsShopee extends Model
{
    protected $fillable = [
        'product_name',
        'item_id',
        'commission_rate',
        'commission',
        'price',
        'sales',
        'image_url',
        'shop_name',
        'product_link',
        'offer_link',
        'period_start_time',
        'period_end_time',
        'price_min',
        'price_max',
        'product_cat_ids',
        'rating_star',
        'price_discount_rate',
        'shop_id',
        'shop_type',
        'seller_commission_rate',
        'shopee_commission_rate',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'commission' => 'decimal:4',
        'price' => 'decimal:2',
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
        'rating_star' => 'decimal:1',
        'seller_commission_rate' => 'decimal:2',
        'shopee_commission_rate' => 'decimal:2',
        'product_cat_ids' => 'array',
        'shop_type' => 'array',
    ];
}
