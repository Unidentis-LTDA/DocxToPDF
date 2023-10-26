<?php

require __DIR__.'/vendor/autoload.php';

const __ROOT_DIR__ = __DIR__;
const URL = 'http://localhost:8333';

use App\Http\Middleware\Api;
use App\Http\Middleware\Maintenance;
use App\Http\Router;
use App\Utils\View;

View::init([
    'URL' => URL
]);

App\Http\Middleware\Queue::setMap([
    'maintenance' => Maintenance::class,
    'api' => Api::class
]);

App\Http\Middleware\Queue::setDefault([
    'maintenance'
]);

$obRouter = new Router(URL);

include __ROOT_DIR__.'/routes/pages.php';
include __ROOT_DIR__.'/routes/api.php';

$obRouter->run()->sendResponse();
