<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGateway extends Model
{
    protected $fillable = [
        'tenant_id',
        'provider',
        'name',
        'is_active',
        'credentials',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credentials' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get a specific credential value
     */
    public function getCredential(string $key, $default = null)
    {
        return $this->credentials[$key] ?? $default;
    }

    /**
     * Set a specific credential value
     */
    public function setCredential(string $key, $value): void
    {
        $credentials = $this->credentials ?? [];
        $credentials[$key] = $value;
        $this->credentials = $credentials;
    }
}
