<?php

namespace Midun\Storage;

class Storage
{
    /**
     * Disk working
     * 
     * @var string
     */
    protected $disk;

    /**
     * Set disk setting
     * 
     * @param string $disk
     * 
     * @return self
     */
    public function disk(string $disk)
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * Check exists file storage
     * 
     * @param string $fileName
     * 
     * @return boolean
     */
    public function exists(string $fileName)
    {
        return file_exists($this->getWorkingDirectory() . $fileName);
    }

    /**
     * Get url for internet
     * 
     * @param string $fileName
     * 
     * @return string
     */
    public function url(string $fileName)
    {
        return config('app.url') . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * Get info of file storage
     * 
     * @param string $fileName
     * 
     * @return \Midun\Storage\FileStorage/null
     */
    public function info(string $fileName)
    {
        if ($this->exists($fileName)) {
            $filePath = $this->getWorkingDirectory() . $fileName;
            $fileStorage = new FileStorage($filePath);

            return $fileStorage->getFileStorageInfo();
        }

        return null;
    }

    /**
     * Get full path of fileName
     * 
     * @param string $fileName
     * 
     * @return string/null
     */
    public function realPath(string $fileName)
    {
        if ($this->exists($fileName)) {
            $filePath = $this->getWorkingDirectory() . $fileName;
            $fileStorage = new FileStorage($filePath);

            return $fileStorage->getRealPath();
        }

        return null;
    }

    /**
     * Copy a file to a new location
     * 
     * @param string $fileName
     * @param string $target
     * 
     * @throws StorageException
     * 
     * @return bool
     */
    public function copy(string $fileName, string $target)
    {
        try {
            if ($this->exists($fileName)) {
                $filePath = $this->getWorkingDirectory() . $fileName;
                $fileStorage = new FileStorage($filePath);

                $realPath = $fileStorage->getRealPath();

                $targetPath = $this->getWorkingDirectory() . $target;

                return copy($realPath, $targetPath);
            }
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * Copy a file to a new location
     * 
     * @param string $fileName
     * @param string $target
     * 
     * @throws StorageException
     * 
     * @return bool
     */
    public function move(string $fileName, string $target)
    {
        try {
            if ($this->exists($fileName)) {
                $filePath = $this->getWorkingDirectory() . $fileName;
                $fileStorage = new FileStorage($filePath);

                $realPath = $fileStorage->getRealPath();

                $targetPath = $this->getWorkingDirectory() . $target;

                return rename($realPath, $targetPath);
            }
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * Upload file to storage
     * 
     * @param \Midun\Services\File $file
     * @param string $fileName
     * @param string $directory
     * 
     * @return string/false
     */
    public function put(\Midun\Services\File $file, $fileName = null, string $directory = null)
    {
        try {
            $tmpName = $file->getTmpName();

            $fileName = !is_null($fileName) ? $fileName : generateRandomString(40);

            $fileName = $fileName . '.' . $file->getFileExtension();

            $uploadTo = !is_null($directory) ? $this->getWorkingDirectory() . $directory . DIRECTORY_SEPARATOR . $fileName : $this->getWorkingDirectory() . $fileName;

            if (true === move_uploaded_file($tmpName, $uploadTo)) {

                return $fileName;
            }

            return false;
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * Upload file to storage as custom information
     * 
     * @param \Midun\Services\File $file
     * @param string $fileName
     * @param string $directory
     * 
     * @return string/false
     */
    public function putAs(\Midun\Services\File $file, string $directory, string $fileName)
    {
        $fullDir = $this->getWorkingDirectory();

        foreach (explode('/', $directory) as $dir) {
            $fullDir .= $dir . DIRECTORY_SEPARATOR;

            if (false === check_dir($fullDir)) {
                mkdir($fullDir, 0755, true);
            }
        }

        return $this->put($file, $fileName, $directory);
    }

    public function delete(string $fileName)
    {
        try {
            if ($this->exists($fileName)) {
                $filePath = $this->getWorkingDirectory() . $fileName;
                $fileStorage = new FileStorage($filePath);

                $realPath = $fileStorage->getRealPath();

                return unlink($realPath);
            }
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * Get file storage instance
     * 
     * @param string $fileName
     * 
     * @throws StorageException
     * 
     * @return \Midun\Storage\FileStorage
     */
    public function get(string $fileName)
    {
        if ($this->exists($fileName)) {
            $filePath = $this->getWorkingDirectory() . $fileName;

            return new FileStorage($filePath);
        }

        throw new StorageException("file {$fileName} doesn't exists");
    }

    public function getCurrentDisk()
    {
        return $this->disk;
    }

    public function getWorkingDirectory()
    {
        return config("storage.{$this->disk}.root");
    }
}
