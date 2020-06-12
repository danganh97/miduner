<?php

namespace App\Main\Services;

class File
{
    private $rawFile;
    private $name;
    private $ext;
    private $size;

    public function __construct($file)
    {
        $this->rawFile = $file;
        $this->name = isset($file['name']) && count(explode('.', $file['name'])) > 1 ? explode('.', $file['name'])[0] : '';
        $this->ext = isset($file['name']) && count(explode('.', $file['name'])) > 1 ? end(explode('.', $file['name'])) : '';
        $this->size = isset($file['size']) ? $file['size'] : 0;
    }

    public function getRawFile()
    {
        return $this->rawFile;
    }

    public function getFileName()
    {
        return $this->name;
    }

    public function getFileExtension()
    {
        return $this->ext;
    }

    public function getFileSize()
    {
        return $this->size;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
