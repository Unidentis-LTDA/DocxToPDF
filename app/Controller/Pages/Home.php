<?php

namespace App\Controller\Pages;
use App\Model\Entity\Organization;
use App\Services\View;

class Home extends Page
{
    public static function getHome()
    {
        $org = new Organization();
        $content = View::render("pages/home", [
            'name' => $org->name,
            'description' => $org->description
        ]);

        return parent::getPage('WDVE - Canal - Home', $content);
    }
}
