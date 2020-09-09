<?php

namespace Midun\Console\Commands\Make;

use Midun\Console\Command;

class MakeModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model';

    /**
     * The console model description.
     *
     * @var string
     */
    protected $description = 'Making model service';

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

        $name = array_shift($options);

        $paseModel = explode('/', $name);
        $namespace = ';';
        $fullDir = base_path('app/Models/');
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
        $defaultModelPath = base_path('midun/Helpers/Init/model.txt');
        $defaultModel = file_get_contents($defaultModelPath);
        $defaultModel = str_replace(':namespace', $namespace, $defaultModel);
        $defaultModel = str_replace(':model', $model, $defaultModel);
        $needleModel = "{$fullDir}$model.php";
        if (!file_exists($needleModel)) {
            $myfile = fopen($needleModel, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultModel);
            fclose($myfile);
            $this->output->printSuccess("Created model {$model}");
        } else {
            $this->output->printWarning("Model {$needleModel} already exists");
        }
    }
}
