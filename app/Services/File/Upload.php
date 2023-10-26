<?php

namespace App\Services\File;

class Upload
{
    private ?string $name;
    private ?string $extension;
    private ?string $type;

    private ?string $tmpName;
    private int $error;
    private int $size;
    private int $duplicates;

    public function __construct(array $file)
    {
        $this->type = $file["type"];
        $this->tmpName = $file["tmp_name"];
        $this->error = $file["error"];
        $this->size = $file["size"];

        $info = pathinfo($file["name"]);
        $this->name = $info["filename"];
        $this->extension = $info["extension"];
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function generateName(): void
    {
        $this->name = time().'-'.rand(100000, 999999).'-'.uniqid();
    }

    public function getBasename(): string
    {
        $extension = strlen($this->extension) ? '.'.$this->extension : '';

        $duplicates = isset($this->duplicates) && $this->duplicates > 0 ? '-'.$this->duplicates : '1';

        return $this->name.$duplicates.$extension;
    }

    public function getPossibleBasename(string $dir, mixed $overwrite): string
    {
        if($overwrite) return $this->getBasename();

        $basename = $this->getBasename();

        if(!file_exists($dir.'/'.$basename)){
            return $basename;
        }
        $this->duplicates++;
        return $this->getPossibleBasename($dir, $overwrite);
    }

    public function upload(string $dir, $overwrite = true){
        if($this->error != 0) return false;

        $path = $dir.'/'.$this->getPossibleBasename($dir, $overwrite);

        return move_uploaded_file($this->tmpName, $path);
    }

    public static function createMultiUpload($files): array
    {
        $uploads = [];
        foreach ($files['name'] as $key => $value){
            $file = [
                'name'=>$files['name'][$key],
                'type'=>$files['type'][$key],
                'tmp_name'=>$files['tmp_name'][$key],
                'error'=>$files['error'][$key],
                'size'=>$files['size'][$key],
            ];
            $uploads[] = new self($file);
        }
        return $uploads;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSize(): int
    {
        return $this->size;
    }

}
