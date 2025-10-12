<?php

namespace App\Models;

use App\Models\Trait\Tenantable;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use Tenantable;

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'image',
        'link_url',
        'link_text',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}