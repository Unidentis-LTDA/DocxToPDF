<?php


use App\Controller\Pages\Home;
use App\Controller\Pages\Sobre;
use App\Http\Response;

$obRouter->get('/', [
    function(){
        return new Response(Home::getHome(), 200);
    }
]);
