<?php

namespace Midun\Configuration;

class Config
{
    /**
     * Caching configuration
     * 
     * @var array
     */
    private $storage = [];

    /**
     * Set config
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return self
     */
    public function setConfig(string $key, $value)
    {
        $this->storage[$key] = $value;
        return $this;
    }

    /**
     * Get config
     * 
     * @param string $key
     * 
     * @return mixed
	 *
	 * @throws ConfigurationException
     */
    public function getConfig(string $key)
    {
        $keys = explode('.', $key);

        $cacheFile = array_shift($keys);

        if (false === $this->checkConfig($cacheFile)) {
            throw new ConfigurationException("File {$cacheFile} not found");
        }

        $value = $this->storage[$cacheFile];

        for ($i = 0; $i <= count($keys) - 1; $i++) {
            if (isset($value[$keys[$i]])) {
                $value = $value[$keys[$i]];
            } else {
                throw new ConfigurationException("Key $keys[$i] not found");
            }
        }

        return $value;
    }

    /**
     * Check exists config
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function checkConfig(string $key)
    {
        return isset($this->storage[$key]);
    }

    /**
     * Get all configs
     * 
     * @return array
     */
    public function all()
    {
        return $this->storage;
    }
}
