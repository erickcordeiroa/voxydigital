<?php

namespace App\Models\Trait;

use App\Models\Tenant;
use App\Scopes\TenantScope;

trait Tenantable
{
    protected static function bootTenantable()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (app()->bound('tenant_id')) {
                $model->tenant_id = app('tenant_id');
            } else {
                throw new \Exception('tenant_id não está definido');
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}