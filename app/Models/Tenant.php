<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'custom_title_color'
    ];
}