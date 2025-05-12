<?php

namespace App\Models;

use App\Models\Trait\Tenantable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Tenantable;

    protected $fillable = [
        'tenant_id',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'note',
        'total',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
