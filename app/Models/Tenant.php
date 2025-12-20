<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'document',
        'domain',
        'whatsapp',
        'logo',
        'cover',
        'status',
        'custom_button',
        'custom_button_text',
        'custom_title_color',
        'tax_fixed',
        'dt_expiration',
        'mp_public_key',
        'mp_access_token'
    ];

    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class);
    }

    public function paymentGateways(): HasMany
    {
        return $this->hasMany(PaymentGateway::class);
    }

    public function activePaymentGateways(): HasMany
    {
        return $this->hasMany(PaymentGateway::class)->where('is_active', true);
    }

    /**
     * Get active payment gateway by provider
     */
    public function getPaymentGateway(string $provider): ?PaymentGateway
    {
        return $this->paymentGateways()
            ->where('provider', $provider)
            ->where('is_active', true)
            ->first();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->first();
    }
}