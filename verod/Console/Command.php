<?php

namespace Midun\Console;

use Midun\Container;
use Midun\Contracts\Console\Command as CommandContract;

abstract class Command implements CommandContract
{
	/**
	 * Ouput of command
	 *
	 * @var string
	 */
	protected $output;

	/**
	 * @var Container
	 */
	protected $app;

	/**
	 * @var string
	 */
	protected $type;

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
     * Options in options
     * 
     * @var array
     */
    protected $options;

    /**
     * Argv
     * 
     * @var array
     */
    protected $argv;

    /**
     * Flag is using cache
     * 
     * @var bool
     */
    protected $usingCache = true;

    /**
     * Other called signatures
     * 
     * @var array
     */
    protected $otherSignatures = [];

    /**
     * Option required
     * 
     * @var array
     */
    protected $required = [];

    /**
     * True format for command
     * 
     * @var string
     */
    protected $format = '';

    /**
     * Helper for using command
     * 
     * @var string
     */
    protected $helper = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        global $argv;

        $this->output = new \Midun\Supports\ConsoleOutput;
        $this->setArgv($argv);
        $this->app = Container::getInstance();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    abstract public function handle();

    /**
     * Set argv
     * 
     * @param array $argv
     */
    public function setArgv($argv = [])
    {
        $this->argv = $argv;
    }

    /**
     * Get argv
     * 
     * @return array
     */
    public function argv()
    {
        return $this->argv;
    }

    /**
     * Get signature
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Get other signatures
     */
    public function getOtherSignatures()
    {
        return $this->otherSignatures;
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
     * @param array $options
     * 
     * @return void
     */
    public function setOptions(array $options)
    {
        $results = [];

        foreach ($options as $opKey => $option) {
            if (strpos($option, '--') !== false) {
                $parsing = explode('=', str_replace('--', '', $option));
                $key = array_shift($parsing);
                $results[$key] = $key == 'help' ? true : array_shift($parsing);
            } else {
                $results[$opKey] = $option;
            }
        }

        $this->options = $results;
    }

    /**
     * Get options of passed command
     * 
     * @return mixed
     */
    public function getOptions(string $property = null)
    {
        if (!is_null($property)) {
            return isset($this->options[$property]) ? $this->options[$property] : false;
        }

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

    /**
     * Check in is verified
     * 
     * @return bool
     */
    public function isVerified()
    {
        foreach ($this->required as $required) {
            if (!isset($this->options[$required])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get format of command
     * 
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Get helper using command
     * 
     * @return string
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
