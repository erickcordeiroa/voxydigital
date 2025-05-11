<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service',
        'name',
        'email',
        'phone',
        'status',
        'message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the service associated with the lead.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Enum for lead statuses.
     *
     * @return array<string>
     */
    public static function statuses(): array
    {
        return [
            'Novo Lead',
            'Contato Iniciado',
            'Sem Resposta',
            'Reunião Agendada',
            'Briefing Realizado',
            'Proposta Enviada',
            'Aguardando Aprovação',
            'Negociação',
            'Fechado',
            'Encerrado',
        ];
    }
}