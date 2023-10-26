<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Services\DocxToPDfService\Pdf;
use App\Services\File\Upload;
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
    public static function onlyDocxTypeFiles(Upload $file): bool
    {
        return in_array($file->getType(), self::$wordMimeTypes, true);
    }
    public static function lessThanFiveMB(Upload $file): bool
    {
        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
        return $maxFileSize > $file->getSize();
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
        if(!self::lessThanFiveMB($file)){
            throw new Exception("Tamanho de arquivo não permitido!", 403);
        }
        $file->generateName();
        if (!$file->upload(__ROOT_DIR__ . '/storage/uploads')) {
            throw new Exception("Não foi possível concluir o upload desse arquivo", 500);
        }
        $name = $file->getBasename();
        $pdfName = str_replace('.docx', '', $name) . ".pdf";
        Pdf::convert($upDir . '/' . $name, $genDir . '/' . $pdfName);
        return $pdfName;
    }
}
