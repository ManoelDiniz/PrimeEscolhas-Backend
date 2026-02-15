<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingProducts extends Model
{
    protected $fillable = [
        'ranking_id',
        'product_id',
        'position',
    ];

    public function ranking()
    {
        return $this->belongsTo(Rankings::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
