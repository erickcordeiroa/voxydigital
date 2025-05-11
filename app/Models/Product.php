<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'tenant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale',
        'status',
        'uri',
        'video',
        'note'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
