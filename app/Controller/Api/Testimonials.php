<?php

namespace App\Controller\Api;

class Testimonials
{
    public static function getDetails($request)
    {
        return [
            'nome' => 'Franklin',
            'testimonial' => 'muito bom'
        ];
    }
}
