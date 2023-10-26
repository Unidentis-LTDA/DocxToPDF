<?php

namespace App\Controller\Pages;
use App\Model\Entity\Organization;
use App\Services\View;

class Sobre extends Page
{
    public static function getAbout()
    {
        $content = View::render("pages/sobre");
        return parent::getPage('WDVE - Canal - Home', $content);
    }
}
