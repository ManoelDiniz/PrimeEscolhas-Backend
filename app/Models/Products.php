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
        'review_count',
        'rating',
        'score',
        'is_featured',
        'views',
        'in_stock',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'score' => 'decimal:1',
        'is_featured' => 'boolean',
        'in_stock' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function affiliateLinks()
    {
        return $this->hasMany(AffiliateLinks::class, 'product_id');
    }

    public function rankings()
    {
        return $this->belongsToMany(Rankings::class)
            ->withPivot('position')
            ->orderBy('position');
    }
}
