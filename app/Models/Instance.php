<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instance extends Model
{
    protected $fillable = [
        'tenant_id',
        'instance',
        'api_key',
        'url',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
