<?php

namespace App\Models;

use App\Models\Trait\Tenantable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use Tenantable;
    
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
