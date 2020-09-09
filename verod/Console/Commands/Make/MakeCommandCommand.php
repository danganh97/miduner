<?php

namespace Midun\Console\Commands\Make;

use Midun\Console\Command;

class MakeCommandCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making command service';

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

        $parseCommand = explode('/', $name);
        $namespace = ';';
        $fullDir = base_path('app/Console/Commands/');
        if (count($parseCommand) > 1) {
            $command = array_pop($parseCommand);
            $namespace = '\\' . implode("\\", $parseCommand) . ';';
            foreach ($parseCommand as $dir) {
                $fullDir .= "{$dir}";
                if (is_dir($fullDir) !== 1) {
                    @mkdir($fullDir, 0777, true);
                    $fullDir .= '/';
                }
            }
        } else {
            $command = $name;
        }
        $defaultCommandPath = base_path('midun/Helpers/Init/command.txt');
        $defaultCommand = file_get_contents($defaultCommandPath);
        $defaultCommand = str_replace(':namespace', $namespace, $defaultCommand);
        $defaultCommand = str_replace(':command', $command, $defaultCommand);
        $needleCommand = "{$fullDir}$command.php";
        if (!file_exists($needleCommand)) {
            $myfile = fopen($needleCommand, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultCommand);
            fclose($myfile);
            $this->output->printSuccess("Created command {$command}");
        } else {
            $this->output->printWarning("Command {$needleCommand} already exists");
        }
    }
}
