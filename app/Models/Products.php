<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'long_description',
        'current_price',
        'old_price',
        'image',
        'affiliate_url',
        'marketplace',
        'review_count',
        'rating',
        'in_stock',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
