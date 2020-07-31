<?php

namespace Main\Console\Commands;

use App\Models\User;
use Main\Console\Command;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear and rewrite caching';

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
        $this->execWriteCache();
        $this->execWriteConfigCache();
        $this->execWriteDataViews();
    }

    /**
     * Clear cache
     * 
     * @return void
     * 
     */
    public function execClearCache()
    {
        $cachePath = BASE . '/cache';
        foreach (scandir($cachePath) as $file) {
            if ($file != '.' && $file != '..' && $file != '.gitignore') {
                exec("rm -rf $cachePath/$file");
            }
        }
        $this->colors->printSuccess("Configuration cache cleared!");
    }

    /**
     * Write the caching
     * 
     * @return void
     * 
     */
    public function execWriteCache()
    {
        $env = readDotENV();
        $cachePath = BASE . '/cache';
        $myfile = fopen("$cachePath/environments.php", "w") or die("Unable to open file!");
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
        $cachePath = BASE . '/cache';
        foreach (scandir(BASE . '/config') as $file) {
            if (strlen($file) > 5) {
                $config = include BASE . '/config/' . $file;
                $myfile = fopen("$cachePath/$file", "w") or die("Unable to open file!");
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
    }

    /**
     * Write data view
     * 
     * @return void
     * 
     */
    public function execWriteDataViews()
    {
        $this->readDataViews("/resources/views");
        $this->colors->printSuccess("Configuration cached successfully!");
    }

    /**
     * Handle array config
     * 
     * @param string $key
     * @param string $myfile
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

    /**
     * Read data views
     * 
     * @param string $folder
     * 
     * @return void
     */
    public function readDataViews($folder)
    {
        $dataViews = array_filter(scandir(BASE . "$folder"), function ($view) {
            return $view !== '.' && $view !== '..';
        });
        foreach ($dataViews as $item) {
            if (strpos($item, '.php') !== false) {
                writeCache($folder, $item);
            } else {
                $this->readDataViews("$folder/$item");
            }
        }
    }
}
