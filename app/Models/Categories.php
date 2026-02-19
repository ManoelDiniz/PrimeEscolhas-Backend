<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Categories::class, 'parent_id');
    }
}
