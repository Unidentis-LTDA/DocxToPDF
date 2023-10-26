<?php

namespace App\Controller\Pages;
use App\Model\Entity\Organization;
use App\Services\View;

class Home extends Page
{
    public static function getHome()
    {
        $content = View::render("pages/home");

        return parent::getPage('WDVE - Canal - Home', $content);
    }
}
