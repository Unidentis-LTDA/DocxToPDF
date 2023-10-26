<?php

use App\Http\Middleware\Api;
use App\Http\Middleware\Maintenance;
use App\Http\Router;
use App\Services\Env\DotEnv;
use App\Services\View;

const __ROOT_DIR__ = __DIR__.'/..';

DotEnv::handle(__ROOT_DIR__.'/.env');

define("URL", getenv('URL'));

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

$routes = glob(__ROOT_DIR__.'/routes/*.php');

foreach ($routes as $route){
    include $route;
}
