<?php

namespace Midun\Translator;

class Translator
{
    /**
     * Caching translator
     * 
     * @var array
     */
    private $storage = [];

    /**s
     * Set config
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return self
     */
    public function setTranslation(string $key, $value)
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
     */
    public function trans(string $key, array $params, string $lang = 'en')
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);
        $key = $file . DIRECTORY_SEPARATOR . $lang;

        if (!$this->checkTranslation($key)) {
            throw new TranslationException("Translator param {$key} not found");
        }
        $value = $this->getTranslation($key);

        for ($i = 0; $i <= count($keys) - 1; $i++) {
            if (isset($value[$keys[$i]])) {
                $value = $value[$keys[$i]];
            } else {
                throw new TranslationException("Key $keys[$i] not found");
            }
        }

        foreach ($params as $key => $param) {
            $value = str_replace(":{$key}", $param, $value);
        }

        return $value;
    }

    /**
     * Get value from key translation.
     * 
     * @param string $key
     * 
     * @return mixed|null
     */
    protected function getTranslation(string $key)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }

    /**
     * Get all storage translation
     * 
     * @return array
     */
    protected function getStorage()
    {
        return $this->storage;
    }

    /**
     * Check exists translation
     * 
     * @param string $key
     * 
     * @return bool
     */
    protected function checkTranslation(string $key)
    {
        return isset($this->storage[$key]);
    }
}
