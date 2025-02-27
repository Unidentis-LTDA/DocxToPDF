<?php

namespace App\Controller\Pages;
use App\Services\View;

class Page
{
    public static function getPage($title, $content)
    {
        return View::render("pages/page", [
            'title' => $title,
            'content' => $content
        ]);
    }
}
