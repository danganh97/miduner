<?php

namespace Midun\Console\Commands;

use Midun\Console\Command;

class ListOfCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display list of commands supported by the application';

    /**
     * Other called signatures
     */
    protected $otherSignatures = [
        'all',
        'help'
    ];

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
        $commands = $this->app->make(\Midun\Contracts\Console\Kernel::class)->all();

        $mapping = [];

        foreach ($commands as $command) {
            $command = $this->app->make($command);
            $signature = $command->getSignature();

            $description = $command->getDescription();

            if (strpos($signature, ':') !== false) {
                list($parent, $name) = explode(':', $signature);
            } else {
                $parent = null;
                $name = $signature;
            }
            array_push($mapping, compact('parent', 'name', 'description'));
        }

        $simpleCommands = array_filter($mapping, function ($command) {
            return is_null($command['parent']);
        });

        $insideCommands = array_filter($mapping, function ($command) {
            return !is_null($command['parent']);
        });

        $newMappingInsideCommands = [];

        foreach ($insideCommands as $command) {
            $newMappingInsideCommands[$command['parent']][] = [
                'name' => $command['name'],
                'description' => $command['description']
            ];
        }

        $version = $this->app::VERSION;

        $this->output->printSuccessNoBackground("Miduner framework version: {$version}");
        echo (PHP_EOL);
        $this->output->printHighlights("Usage:");
        $this->output->printSuccessNoBackground("   command [options] [arguments]");
        echo (PHP_EOL);
        $this->output->printHighlights("Options:");
        $this->output->printSuccessNoBackground("   -h, --help        Display this help message");
        echo (PHP_EOL);
        $this->output->printHighlights("List of supported commands:");

        foreach ($simpleCommands as $command) {
            $this->output->printSuccessNoBackground("   {$command['name']}" . $this->makeSpace(15 - strlen($command['name'])) . "{$command['description']}");
        }

        foreach ($newMappingInsideCommands as $parent => $commands) {
            $this->output->printHighlights($parent);
            foreach ($commands as $command) {
                $this->output->printSuccessNoBackground("   {$command['name']}" . $this->makeSpace(15 - strlen($command['name'])) . "{$command['description']}");
            }
        }
    }

    /**
     * Make space
     * 
     * @param int $max
     * @param string $specific
     * 
     * @return string
     */
    public function makeSpace(int $max, string $specific = ' ')
    {
        $space = '';

        for ($i = 1; $i <= $max; $i++) {
            $space .= $specific;
        }

        return $space;
    }
}
