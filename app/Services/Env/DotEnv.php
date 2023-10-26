<?php

namespace App\Services\Env;


use InvalidArgumentException;
use RuntimeException;

class DotEnv
{
    public static function handle(string $filename)
    {
        if (!file_exists($filename))
            throw new InvalidArgumentException(sprintf("O arquivo %s não foi encontrado.", $filename));

        if (!is_readable($filename))
            throw new RuntimeException(sprintf("O arquivo %s não está disponível para leitura", $filename));

        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0)
                continue;


            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
