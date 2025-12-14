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
}