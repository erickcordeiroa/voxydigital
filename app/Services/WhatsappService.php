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
        $msg = "Olá, somos da empresa {$tenant->name}. \n";
        $msg .= "Seu pedido foi recebido com sucesso! Aguarde nosso contato.";

        return Http::post($url, [
            'token' => $token,
            'to' => $to,
            'body' => $msg,
        ]);
    }
}