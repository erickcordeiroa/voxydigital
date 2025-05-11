<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
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
