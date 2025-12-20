<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'payment_gateway_id',
        'plan_name',
        'amount',
        'currency',
        'status',
        'billing_cycle',
        'starts_at',
        'ends_at',
        'next_billing_date',
        'abacatepay_subscription_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'next_billing_date' => 'datetime',
        'metadata' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->ends_at !== null && $this->ends_at->isPast());
    }

    /**
     * Check if subscription can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->status !== 'cancelled';
    }

    /**
     * Boot the model
     */
    protected static function boot(): void
    {
        parent::boot();

        // Prevenir exclusão de assinaturas canceladas
        static::deleting(function ($subscription) {
            if ($subscription->status === 'cancelled') {
                throw new \Exception('Assinaturas canceladas não podem ser excluídas. Apenas deixe-as inativas.');
            }
        });
    }
}
