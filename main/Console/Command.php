<?php

namespace Main\Console;

use Main\Contracts\Console\Command as CommandContract;

abstract class Command implements CommandContract
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * Type of passed command
     */
    protected $type;

    /**
     * Options in options
     */
    protected $options;

    /**
     * Argv
     */
    protected $argv;

    /**
     * Flag is using cache
     */
    protected $usingCache = true;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->colors = new \Main\Colors;

        global $argv;

        $this->argv = $argv;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    abstract public function handle();

    /**
     * Get signature
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Get description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type of passed command
     * @param string $type
     * 
     * @return void
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Get type of passed command
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set options of passed command
     * @param mixed $options
     * 
     * @return void
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Get options of passed command
     * 
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get check is using cache
     * 
     * @return boolean
     */
    public function isUsingCache()
    {
        return $this->usingCache;
    }
}
