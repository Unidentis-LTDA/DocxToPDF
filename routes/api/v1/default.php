<?php

use App\Controller\Api\Api;
use App\Http\Response;

$obRouter->get('/api/v1', [
    function ($request) {
        return new Response(Api::getDetails($request), 200, 'application/json');
    }
]);
