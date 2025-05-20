<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    public static function send($to, $message)
    {
        $url = config('services.whatsapp.url') . '/messages/chat';
        $token = config('services.whatsapp.token');

        return Http::post($url, [
            'token' => $token,
            'to' => $to,
            'body' => $message,
        ]);
    }

    public static function sendToClient($to)
    {
        $url = config('services.whatsapp.url') . '/messages/chat';
        $token = config('services.whatsapp.token');

        $tenant = app('tenant');
        $msg = "Olá, somos da empresa Voxy Digital. \n";
        $msg .= "Você realizou um pedido para a empresa {$tenant->name}.\n";
        $msg .= "Informaremos o status do pedido pelo whatsapp!.\n";
        $msg .= "Agradecemos pela preferência!";

        return Http::post($url, [
            'token' => $token,
            'to' => $to,
            'body' => $msg,
        ]);
    }

    
}