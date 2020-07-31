<?php

namespace Main\Console\Commands;

use Main\Console\Command;

class HandleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate application key';

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
        $options = $this->getOptions();

        switch ($this->getType()) {
            case 'controller':
                $this->execMakeController($options[0]);
                break;
            case 'model':
                $this->execMakeModel($options[0]);
                break;
            case 'request':
                $this->execMakeRequest($options[0]);
                break;
            case 'migration':
                foreach ($options as $option) {
                    if (strpos($option, '--table=') !== false) {
                        $table = str_replace('--table=', '', $this->argv[2]);
                    }
                }
                $this->execMakeMigration($table);
        }
    }

    /**
     * Make controller command
     * 
     * @param string $name
     * 
     * @return boolean
     */
    public function execMakeController(string $name)
    {
        $paseController = explode('/', $name);
        $namespace = ';';
        $fullDir = BASE . '/app/Http/Controllers/';
        if (count($paseController) > 1) {
            $controller = array_pop($paseController);
            $namespace = '\\' . implode("\\", $paseController) . ';';
            foreach ($paseController as $dir) {
                $fullDir .= "{$dir}";
                if (is_dir($fullDir) !== 1) {
                    @mkdir($fullDir, 0777, true);
                    $fullDir .= '/';
                }
            }
        } else {
            $controller = $name;
        }
        $defaultControllerPath = BASE. '/main/Helpers/Init/controller.txt';
        $defaultController = file_get_contents($defaultControllerPath);
        $defaultController = str_replace(':namespace', $namespace, $defaultController);
        $defaultController = str_replace(':controller', $controller, $defaultController);
        $needleController = "{$fullDir}$controller.php";
        if (!file_exists($needleController)) {
            $myfile = fopen($needleController, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultController);
            fclose($myfile);
            $this->colors->printSuccess("Created controller {$controller}");
        } else {
            $this->colors->printWarning("Controller {$needleController} already exists");
        }
        return true;
    }

    /**
     * Make model command
     * 
     * @param string $name
     * 
     * @return boolean
     */
    public function execMakeModel(string $name)
    {
        $paseModel = explode('/', $name);
        $namespace = ';';
        $fullDir = BASE . '/app/Models/';
        if (count($paseModel) > 1) {
            $model = array_pop($paseModel);
            $namespace = '\\' . implode("\\", $paseModel) . ';';
            foreach ($paseModel as $dir) {
                $fullDir .= "{$dir}";
                if (is_dir($fullDir) !== 1) {
                    @mkdir($fullDir, 0777, true);
                    $fullDir .= '/';
                }
            }
        } else {
            $model = $name;
        }
        $defaultModelPath = BASE. '/main/Helpers/Init/model.txt';
        $defaultModel = file_get_contents($defaultModelPath);
        $defaultModel = str_replace(':namespace', $namespace, $defaultModel);
        $defaultModel = str_replace(':model', $model, $defaultModel);
        $needleModel = "{$fullDir}$model.php";
        if (!file_exists($needleModel)) {
            $myfile = fopen($needleModel, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultModel);
            fclose($myfile);
            $this->colors->printSuccess("Created model {$model}");
        } else {
            $this->colors->printWarning("Model {$needleModel} already exists");
        }
        return true;
    }

    /**
     * Make request command
     * 
     * @param string $request
     * 
     * @return boolean
     */
    public function execMakeRequest(string $request)
    {
        $paseRequest = explode('/', $request);
        $namespace = ';';
        $fullDir = BASE . '/app/Http/Requests/';
        if (count($paseRequest) > 1) {
            $request = array_pop($paseRequest);
            $namespace = '\\' . implode("\\", $paseRequest) . ';';
            foreach ($paseRequest as $dir) {
                $fullDir .= "{$dir}";
                if (is_dir($fullDir) !== 1) {
                    mkdir($fullDir, 0777, true);
                    $fullDir .= '/';
                }
            }
        }
        $defaultRequestPath = BASE. '/main/Helpers/Init/request.txt';
        $defaultRequest = file_get_contents($defaultRequestPath);
        $defaultRequest = str_replace(':request', $request, $defaultRequest);
        $defaultRequest = str_replace(':namespace', $namespace, $defaultRequest);
        $defaultRequest = str_replace(':Request', ucfirst($request), $defaultRequest);
        $name = "{$request}.php";
        $needleRequest = "{$fullDir}$name";
        if (!file_exists($needleRequest)) {
            $myfile = fopen($needleRequest, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultRequest);
            fclose($myfile);
            $this->colors->printSuccess("Created Request {$request}");
        } else {
            $this->colors->printWarning("Request {$needleRequest} already exists");
        }
        return true;
    }

    /**
     * Make migrate command
     * 
     * @param string $table
     * 
     * @return boolean
     */
    public function execMakeMigration(string $table)
    {
        $defaultMigratePath = BASE. '/main/Helpers/Init/migrate.txt';
        $defaultMigrate = file_get_contents($defaultMigratePath);
        $defaultMigrate = str_replace(':table', $table, $defaultMigrate);
        $defaultMigrate = str_replace(':Table', ucfirst($table), $defaultMigrate);
        $fullDir = BASE . '/database/migration/';
        $date = date('Ymd_His');
        $name = "{$date}_{$table}_migration.php";
        $needleTable = "{$fullDir}$name";
        if (!file_exists($needleTable)) {
            $myfile = fopen($needleTable, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultMigrate);
            fclose($myfile);
            $this->colors->printSuccess("Created Table {$table}");
        } else {
            $this->colors->printWarning("Table {$needleTable} already exists");
        }
        return true;
    }
}
