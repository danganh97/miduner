<?php

namespace Midun\Storage;

class FileStorage
{
    /**
     * Dir name 
     * 
     * @var string
     */
    protected $dirName;
    /**
     * File name 
     * 
     * @var string
     */
    protected $fileName;
    /**
     * Extension 
     * 
     * @var string
     */
    protected $extension;
    /**
     * Base name 
     * 
     * @var string
     */
    protected $baseName;
    /**
     * Real path 
     * 
     * @var string
     */
    protected $realPath;
    /**
     * Mime 
     * 
     * @var string
     */
    protected $mime;
    /**
     * Encoding 
     * 
     * @var string
     */
    protected $encoding;
    /**
     * File size 
     * 
     * @var int
     */
    protected $size;
    /**
     * File size string 
     * 
     * @var string
     */
    protected $sizeString;
    /**
     * Added time 
     * 
     * @var int
     */
    protected $atime;
    /**
     * Modified time 
     * 
     * @var int
     */
    protected $mtime;

    /**
     * Constructor of File Storage
     * 
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $info = pathinfo($filePath);

        $this->dirName = $info['dirname'];

        $this->fileName = $info['filename'];

        $this->extension = $info['extension'];

        $this->baseName = $info['basename'];

        $this->realPath = realpath($filePath);

        $this->mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath);

        $this->encoding = finfo_file(finfo_open(FILEINFO_MIME_ENCODING), $filePath);

        $stat = stat($filePath);

        $this->size = $stat['size'];

        $this->sizeString = $this->formatBytes($this->size);

        $this->atime = $stat['atime'];

        $this->mtime = $stat['mtime'];
    }

    /**
     * @param int => $size = valor em bytes a ser format
     * 
     * @return string
     */
    private function formatBytes(int $size)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), 2) . '' . $suffixes[floor($base)];
    }

    /**
     * Get dir name
     * 
     * @return string
     */
    public function getDirName()
    {
        return $this->dirName;
    }

    /**
     * Get file name
     * 
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get file extension
     * 
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get base name
     * 
     * @return string
     */
    public function getBaseName()
    {
        return $this->baseName;
    }

    /**
     * Get real path
     * 
     * @return string
     */
    public function getRealPath()
    {
        return $this->realPath;
    }

    /**
     * Get mime
     * 
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Get encoding
     * 
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Get size
     * 
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get size under string
     * 
     * @return string
     */
    public function getSizeString()
    {
        return $this->sizeString;
    }

    /**
     * Get added time
     * 
     * @return int
     */
    public function getAddedTime()
    {
        return $this->atime;
    }

    /**
     * Get modified time
     * 
     * @return int
     */
    public function getModifiedTime()
    {
        return $this->mtime;
    }

    /**
     * Get info of file storage
     * 
     * @return array
     */
    public function getFileStorageInfo()
    {
        return [
            'dir_name' => $this->getDirName(),
            'file_name' => $this->getFileName(),
            'extension' => $this->getExtension(),
            'base_name' => $this->getBaseName(),
            'real_path' => $this->getRealPath(),
            'mime' => $this->getMime(),
            'encoding' => $this->getEncoding(),
            'size' => $this->getSize(),
            'sizeString' => $this->getSizeString(),
            'add_time' => gmdate("Y-m-d H:i:s", $this->getAddedTime()),
            'modified_time' => gmdate("Y-m-d H:i:s", $this->getModifiedTime()),
        ];
    }
}
