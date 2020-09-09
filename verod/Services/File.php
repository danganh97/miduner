<?php

namespace Midun\Services;

class File
{
    /**
     * Raw file
     * 
     * @var mixed
     */
    private $rawFile;

    /**
     * Name of file
     * 
     * @var string
     */
    private $name;

    /**
     * Extension
     * 
     * @var string
     */
    private $ext;

    /**
     * Size
     * 
     * @var int
     */
    private $size;

    /**
     * Tmp name of file
     * 
     * @var string
     */
    private $tmp_name;

    /**
     * Constructor
     * 
     * @param mixed $file
     */
    public function __construct($file)
    {
        $this->rawFile = $file;

        foreach ($file as $key => $value) {
            $this->$key = $value;
        }

        $parseName = explode('.', $file['name']);

        $this->ext = end($parseName);

        $this->name = str_replace('.' . $this->ext, '', $file['name']);

        $this->size = isset($file['size']) ? $file['size'] : 0;

        $this->tmp_name = $file['tmp_name'];
    }

    /**
     * Get raw file
     * 
     * @return mixed
     */
    public function getRawFile()
    {
        return $this->rawFile;
    }

    /**
     * Get file name
     * 
     * @return string
     */
    public function getFileName()
    {
        return $this->name;
    }

    /**
     * Get extension
     * 
     * @return string
     */
    public function getFileExtension()
    {
        return $this->ext;
    }

    /**
     * Get file size
     * 
     * @return int
     */
    public function getFileSize()
    {
        return $this->size;
    }

    /**
     * Get tmp_name of file
     * 
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmp_name;
    }

    /**
     * Get property
     * 
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }
}
