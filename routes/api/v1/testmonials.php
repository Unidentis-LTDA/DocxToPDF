<?php

use App\Controller\Api\Testimonials;
use App\Http\Response;

$obRouter->get('/api/v1/testimonials', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(Testimonials::getDetails($request), 200, 'application/json');
    }
]);
