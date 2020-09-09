<?php

namespace Midun\Console\Commands\Make;

use Midun\Console\Command;

class MakeRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:request';

    /**
     * The console request description.
     *
     * @var string
     */
    protected $description = 'Making request service';

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

        $request = array_shift($options);

        $paseRequest = explode('/', $request);
        $namespace = ';';
        $fullDir = base_path('app/Http/Requests/');
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
        $defaultRequestPath = base_path('midun/Helpers/Init/request.txt');
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
            $this->output->printSuccess("Created Request {$request}");
        } else {
            $this->output->printWarning("Request {$needleRequest} already exists");
        }
    }
}
