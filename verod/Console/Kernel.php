<?php

namespace Midun\Console;

use Midun\Container;
use Midun\Application;
use Midun\Console\Commands\ListOfCommand;
use Midun\Console\Commands\MigrateCommand;
use Midun\Console\Commands\Db\DbSeedCommand;
use Midun\Console\Commands\Live\LiveCodeCommand;
use Midun\Console\Commands\Exec\ExecQueryCommand;
use Midun\Console\Commands\Jwt\JwtInstallCommand;
use Midun\Console\Commands\Make\MakeModelCommand;
use Midun\Console\Commands\View\ViewClearCommand;
use Midun\Console\Commands\CreateServerCliCommand;
use Midun\Console\Commands\Key\KeyGenerateCommand;
use Midun\Console\Commands\Queue\QueueWorkCommand;
use Midun\Console\Commands\Route\RouteListCommand;
use Midun\Console\Commands\Make\MakeCommandCommand;
use Midun\Console\Commands\Make\MakeRequestCommand;
use Midun\Console\Commands\Queue\QueueTableCommand;
use Midun\Console\Commands\Config\ConfigCacheCommand;
use Midun\Console\Commands\Config\ConfigClearCommand;
use Midun\Console\Commands\Make\MakeMigrationCommand;
use Midun\Contracts\Console\Kernel as KernelContract;
use Midun\Console\Commands\Make\MakeControllerCommand;
use Midun\Console\Commands\Storage\StorageLinkCommand;
use Midun\Console\Commands\Schedule\ScheduleRunCommand;
use Midun\Console\Commands\Migrate\MigrateRollbackCommand;
use Midun\Console\Commands\Development\DevelopmentModeCommand;

class Kernel implements KernelContract
{
	/**
	 * @var Container
	 */
	protected $app;

	/**
	 * Console output
	 *
	 * @var \Midun\Supports\ConsoleOutput
	 */
	protected $output;

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
     * @var \Midun\Application
     */
    protected $application;

    /**
     * Framework type
     * 
     * @var string
     */
    const FRAMEWORK_TYPE = 'hustle';

    /**
     * List of application commands
     * 
     * @var array
     */
    protected $appCommands = [
        MigrateCommand::class,
        MigrateRollbackCommand::class,
        CreateServerCliCommand::class,
        MakeCommandCommand::class,
        MakeControllerCommand::class,
        MakeMigrationCommand::class,
        MakeModelCommand::class,
        MakeRequestCommand::class,
        KeyGenerateCommand::class,
        ScheduleRunCommand::class,
        RouteListCommand::class,
        QueueTableCommand::class,
        JwtInstallCommand::class,
        QueueWorkCommand::class,
        DbSeedCommand::class,
        ExecQueryCommand::class,
        LiveCodeCommand::class,
        ListOfCommand::class,
        ConfigCacheCommand::class,
        ViewClearCommand::class,
        ConfigClearCommand::class,
        StorageLinkCommand::class,
        DevelopmentModeCommand::class
    ];

    /**
     * Constructor of Kernel
     */
    public function __construct()
    {
        global $argv;

        array_shift($argv);

        if (empty($argv)) array_push($argv, 'list');

        $this->setArgv($argv);

        $this->app = Container::getInstance();

        $this->output = new \Midun\Supports\ConsoleOutput;

		$this->app->singleton(Application::class, function ($app) {
            return new Application($app);
        });

        $this->application = $this->app->make(Application::class);
    }

    /**
     * Handle execute command
     * 
     * @return void
     * 
     * @throws ConsoleException
     */
    public function handle(array $argv = [])
    {
        $argv = empty($argv) ? $this->argv() : $argv;
        $type = strtolower(array_shift($argv));
        foreach ($this->all() as $command) {

            $command = $this->app->make($command);

            if ($type == $command->getSignature() || in_array($type, $command->getOtherSignatures())) {
                if (!empty($argv)) {
                    $command->setOptions(array_values($argv));
                }
                if (isset($command->getOptions()['help']) && $command->getOptions()['help'] === true) {
                    $helper = $command->getHelper();
                    if ($helper !== '') {
                        $message = $helper;
                    } else {
                        $message = $command->getFormat();
                    }
                    $this->output->printSuccess($message);
                    exit(0);
                }

                if (!$command->isVerified()) {
                    if ($command->getFormat() !== '') {
                        $message = "You're missing some arguments please follow\n" . $command->getFormat();
                    } else {
                        $message = "You're missing some arguments when run command " . $command->getDescription();
                    }
                    $this->output->printWarning($message);
                    exit(0);
                }
                if ($command->isUsingCache()) {
                    if (!$this->caching()) {
                        throw new ConsoleException("You're missing register caching, please run `hustle config:cache` first !\n");
                    }
                    $this->application->run();
                }
                $command->setArgv($this->argv);

                $command->handle();
                exit(1);
            }
        }

        $this->output->printError("Bash {$type} is not supported.");
    }

    /**
     * Call a single command
     * 
     * @param string $command
     * 
     * @return void
	 *
	 * @throws ConsoleException
     */
    public function call(string $command, array $options = [])
    {
        $command = $this->app->make($command);

        if (!empty($options)) {
            $command->setOptions($options);
        }

        if ($command->isUsingCache()) {
            if (!$this->caching()) {
                throw new ConsoleException("Please generate caching files !");
            }
            $this->application->run();
        }
        $command->getArgv($this->argv);

        $command->handle();
        exit(1);
    }

    /**
     * Check exists application caching
     * 
     * @return boolean
     */
    public function caching()
    {
        return cacheExists('app.php');
    }

    /**
     * Get all command lists
     * 
     * @return array
     */
    public function all()
    {
        return array_merge($this->commands, $this->appCommands);
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
     * Set argv
     * 
     * @param array $argv
     */
    protected function setArgv(array $argv)
    {
        $this->argv = $argv;
    }
}
