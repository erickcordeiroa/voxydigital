<?php

namespace App\Models;

use App\Models\Trait\Tenantable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use Tenantable;

    protected $fillable = [
        'id',
        'tenant_id',
        'name',
        'slug',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'bool',
        'created_at' => 'datetime'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
