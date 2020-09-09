<?php

namespace Midun\Console\Commands\Config;

use Midun\Console\Command;

class ConfigClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear and rewrite config, caching';

    /**
     * Flag check using cache
     * @var boolean
     */
    protected $usingCache = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle the command
     * 
     * @return void
     */
    public function handle()
    {
        $this->execClearCache();
        $this->writeEnvironmentCache();
        $this->execWriteConfigCache();
        $this->output->printSuccess("Configuration cleared successfully!");
    }

    /**
     * Clear cache
     * 
     * @return void
     * 
     */
    public function execClearCache()
    {
        $path = cache_path();

        if (false == is_dir($path)) {
            mkdir($path);
        }
        $items = items_in_folder($path);

        foreach ($items as $file) {
            if(\file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Write the caching
     * 
     * @return void
     * 
     */
    public function writeEnvironmentCache()
    {
        $env = readDotENV();
        $cacheEnvironmentPath = cache_path('environments.php');

        $myfile = fopen($cacheEnvironmentPath, "w") or die("Unable to open file!");
        fwrite($myfile, "<?php\n");
        fwrite($myfile, "return array(\n");
        foreach ($env as $key => $value) {
            $key = trim($key);
            $value = trim($value);
            fwrite($myfile, "    '{$key}' => '{$value}',\n");
        }
        fwrite($myfile, ");");
    }

    /**
     * Write config caching
     * 
     * @return void
     * 
     */
    public function execWriteConfigCache()
    {
        $cachePath = cache_path();
        $configPath = config_path();
        $items = items_in_folder($configPath);
        foreach ($items as $file) {
            $config = include $file;
            $file = str_replace($configPath, '', $file);
            $myfile = fopen($cachePath . $file, "w") or die("Unable to open file!");
            fwrite($myfile, "<?php\n");
            fwrite($myfile, "return array(\n");
            foreach ($config as $key => $value) {
                if (is_array($value)) {
                    $this->_handleArrayConfig($key, $myfile, $value);
                } else {
                    fwrite($myfile, "'$key' => '{$value}',\n");
                }
            }
            fwrite($myfile, ");");
        }
    }

    /**
     * Handle array config
     * 
     * @param string $key
     * @param mixed $myfile
     * @param array $values
     * 
     * @return void
     * 
     */
    public function _handleArrayConfig($key, $myfile, array $values)
    {
        fwrite($myfile, "'{$key}' => array(\n");
        foreach ($values as $k => $v) {
            if (is_array($v)) {
                $this->_handleArrayConfig($k, $myfile, $v);
            } else {
                fwrite($myfile, "        '{$k}' => '{$v}',\n");
            }
        }
        fwrite($myfile, "    ),\n");
    }
}
