<?php

namespace App\Controller\Api;

class Api
{
    public static function getDetails($request)
    {
        return [
            'nome' => 'API - WDEV',
            'versao' => 'v1.0.0',
            'autor' => 'William Costa',
            'email' => 'canalwdev@gmail.com'
        ];
    }
}
