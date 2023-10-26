<?php

use App\Controller\Api\Convert;
use App\Http\Response;

$obRouter->post('/api/v1/convert', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(Convert::handle($request), 200, 'application/json');
    }
]);
