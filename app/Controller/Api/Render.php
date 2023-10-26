<?php

namespace App\Controller\Api;

use App\Http\Request;

class Render
{
    public static function handle(Request $request)
    {
        $genDir = __ROOT_DIR__ . '/storage/generateds';
        $data = $request->getPostVars();
        $file = $data["file"];
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=filename.pdf");
        @readfile($genDir . '/' . $file);
        echo "<pre>"; print_r($data); echo "</pre>" . PHP_EOL; exit;
    }
}
