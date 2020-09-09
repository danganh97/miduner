<?php

namespace Midun\Console\Scheduling;

use Midun\Console\ConsoleException;
use Midun\Container;

class Schedule
{
	/**
	 * @var \Midun\Container
	 */
	protected $app;
    /**
     * List of crontab entries
     * 
     * @var array
     */
    protected $tasks = [];

    /**
     * Signature of command line
     * 
     * @var string
     */
    protected $command;

    /**
     * Expression crontab
     * 
     * @var string
     */
    protected $expression;

    /**
     * Cli php using command
     * 
     * @var string
     */
    protected $cli = 'php';

    /**
     * Output of command
     * 
     * @var string
     */
    protected $output;

    /**
     * Constructor of Schedule
     */
    public function __construct()
    {
        $this->app = Container::getInstance();
    }

    /**
     * Set command
     * 
     * @param string $command
     * 
     * @return self
     */
    public function command(string $command)
    {
        $this->setScheduleAndClear();
        $this->command = $this->app->make($command)->getSignature();
        return $this;
    }

    /**
     * Set new schedule and clear properties
     * 
     * @return void
     */
    public function setScheduleAndClear()
    {
        $this->tasks[] = [
            'command' => $this->getCommand(),
            'expression' => $this->getExpression(),
            'output' => ">> {$this->getOutput()} 2>&1",
            'cli' => $this->getCli()
        ];

        $this->refreshProps();
    }

    /**
     * Refresh properties
     * 
     * @return void
     */
    private function refreshProps()
    {
        $this->command = null;
        $this->expression = null;
        $this->cli = 'php';
        $this->output = null;
    }

    /**
     * Get command
     * 
     * @return string
     */
    private function getCommand()
    {
        return $this->command;
    }

    /**
     * Get expression
     * 
     * @return string
     */
    private function getExpression()
    {
        return $this->expression;
    }

    /**
     * Get output
     * 
     * @return string
     */
    private function getOutput()
    {
        return !is_null($this->output) ? $this->output : storage_path('logs/schedule.log');
    }

    /**
     * Get cli
     * 
     * @return string
     */
    private function getCli()
    {
        return $this->cli;
    }

    /**
     * Collect all schedule
     * 
     * @return array
     */
    public function collect()
    {
        $this->setScheduleAndClear();
        return $this->tasks;
    }

    /**
     * Set output file for command
     * 
     * @param string $output
     * 
     * @return self
     */
    public function output(string $output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Set php cli using command
     * 
     * @param string $cli
     * 
     * @return self
     */
    public function cli(string $cli)
    {
        $this->cli = $cli;
        return $this;
    }

    /**
     * Handle all type of command schedule
     * 
     * @param string $function
     * @param array $args
     * 
     * @return self
	 * 
	 * @throws ConsoleException
     */
    public function __call($function, $args)
    {
        try {
            $args = isset($args[0]) ? $args[0] : null;

            switch ($function) {
                case 'everyMinute':
                    $expression = '* * * * *';
                    break;
                case 'everyMinutes':
                    $expression = "*/{$args} * * * *";
                    break;
                case 'hourly':
                    $expression = "0 * * * *";
                    break;
                case 'hourlyAt':
                    $expression = "{$args} * * * *";
                    break;
                case 'everyHours':
                    $expression = "0 */{$args} * * *";
                    break;
                case 'daily':
                    $expression = "0 0 * * *";
                    break;
                case 'dailyAt':
                    if (strpos($args, ':') !== false) {
                        list($hour, $min) = explode(':', $args);
                        $expression = "{$min} {$hour} * * *";
                    } else {
                        $expression = "0 $args * * *";
                    }
                    break;
                case 'weekly':
                    $expression = "0 0 * * 0";
                    break;
                case 'monthly':
                    $expression = "0 0 1 * *";
                    break;
                case 'yearly':
                    $expression = "0 0 1 1 *";
                    break;
                case 'cron':
                    $expression = $args;
                    break;
                default:
                    throw new ConsoleException("Method {$function} does not exist");
            }

            $this->expression = $expression;

            return $this;
        } catch (ConsoleException $e) {
            throw new ConsoleException($e->getMessage());
        }
    }
}
