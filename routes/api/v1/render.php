<?php

use App\Controller\Api\Render;
use App\Http\Response;

$obRouter->post('/api/v1/render', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(Render::handle($request), 200, 'application/json');
    }
]);
