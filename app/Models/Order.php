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
        'customer_email',
        'customer_phone',
        'delivery_address',
        'note',
        'total',
        'status',
        'tax_fixed',
        'payment_method',
        'payment_id',
        'payment_status',
        'qr_code',
        'qr_code_base64',
    ];

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
