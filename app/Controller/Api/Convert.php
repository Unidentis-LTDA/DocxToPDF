<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Utils\DocxToPDfService\Pdf;
use App\Utils\File\Upload;
use Exception;

class Convert
{
    private static array $wordMimeTypes = array(
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'application/vnd.ms-word.document.macroEnabled.12',
        'application/vnd.ms-word.template.macroEnabled.12',
    );
    public static function onlyDocxTypeFiles(Upload $file)
    {
        return in_array($file->getType(), self::$wordMimeTypes, true);
    }

    /**
     * @throws Exception
     */
    public static function handle(Request $request)
    {
        $upDir = __ROOT_DIR__ . '/storage/uploads';
        $genDir = __ROOT_DIR__ . '/storage/generateds';
        $file = $request->getFile();
        if(!self::onlyDocxTypeFiles($file)){
            throw new Exception("Tipo de arquivo não permitido!", 403);
        }
        $file->generateName();
        if (!$file->upload(__ROOT_DIR__ . '/storage/uploads')) {
            throw new Exception("Não foi possível concluir o upload desse arquivo", 500);
        }
        $name = $file->getBasename();
        Pdf::convert($upDir . '/' . $name, $genDir . '/' . str_replace('.docx', '', $name) . ".pdf");
        return $name;
    }
}
