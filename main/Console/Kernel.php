<?php

namespace Main\Console;

use Main\Container;
use Main\Http\Exceptions\AppException;
use Main\Console\Commands\MigrationCommand;
use Main\Console\Commands\RunSeederCommand;
use Main\Console\Commands\ClearCacheCommand;
use Main\Console\Commands\HandleMakeCommand;
use Main\Console\Commands\GenerateAppKeyCommand;
use Main\Console\Commands\CreateServerCliCommand;
use Main\Contracts\Console\Kernel as KernelContract;

class Kernel implements KernelContract
{
    /**
     * Argv of shell
     * 
     * @var array
     */
    protected $argv;

    /**
     * List of after run application commands
     * 
     * @var array
     */
    protected $commands = [];

    /**
     * Instance of the application
     * 
     * @var \Main\Application
     */
    protected $application;

    /**
     * List of application commands
     * 
     * @var array
     */
    protected $appCommands = [
        ClearCacheCommand::class,
        RunSeederCommand::class,
        MigrationCommand::class,
        CreateServerCliCommand::class,
        GenerateAppKeyCommand::class,
        HandleMakeCommand::class
    ];

    /**
     * Constructor of Kernel
     */
    public function __construct()
    {
        global $argv;

        $this->argv = $argv;

        $this->app = Container::getInstance();

        $this->colors = new \Main\Colors;

        $this->application = new \Main\Application();
    }

    /**
     * Handle execute command
     * 
     * @return void
     * 
     * @throws \Main\Http\Exceptions\AppException
     */
    public function handle()
    {
        $argv = $this->argv;

        foreach ($this->all() as $command) {

            $command = $this->app->make($command);

            $requestArgv = strtolower($argv[1]);

            if (strpos($requestArgv, ':') !== false) {
                list($argvFromExplode, $type) = explode(':', $requestArgv);
                $command->setType($type);
            }

            if ($requestArgv == $command->getSignature() || isset($argvFromExplode) && $argvFromExplode == $command->getSignature()) {
                unset($argv[0]);
                unset($argv[1]);

                if (!empty($argv)) {
                    $command->setOptions(array_values($argv));
                }

                if ($command->isUsingCache()) {
                    if (!file_exists(BASE . '/cache/app.php')) {
                        throw new AppException("Please generate caching files !");
                    }
                    $this->application->run();
                }

                return $command->handle();
            }
        }

        throw new \Main\Http\Exceptions\AppException($this->colors->printError("Bash {$argv[1]} is not supported."));
    }

    /**
     * Output of the command
     */
    public function output()
    { }

    /**
     * Get all command lists
     * 
     * @return array
     */
    public function all()
    {
        return array_merge($this->commands, $this->appCommands);
    }
}
