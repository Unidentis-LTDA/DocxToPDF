<?php


use App\Controller\Pages\Home;
use App\Controller\Pages\Sobre;
use App\Http\Response;

$obRouter->get('/', [
    function(){
        return new Response(Home::getHome(), 200);
    }
]);

$obRouter->get('/home', [
    function(){
        return new Response(Home::getHome(), 200);
    }
]);

$obRouter->get('/sobre', [
    function(){
        return new Response(Sobre::getAbout(), 200);
    }
]);

$obRouter->get('/sobre/{id}', [
    function(){
        return new Response(Sobre::getAbout(), 200);
    }
]);
