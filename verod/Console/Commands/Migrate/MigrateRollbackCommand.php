<?php

namespace Midun\Console\Commands\Migrate;

use Midun\Console\Command;

class MigrateRollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the migration database tables';

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
        $files = scandir(database_path('migration'), 1);
        arsort($files);
        foreach ($files as $file) {
            if (strlen($file) > 5) {
                include database_path("migration/{$file}");
                $classes = get_declared_classes();
                $class = end($classes);
                $object = new $class;
                if (method_exists($object, 'down')) {
                    $this->output->printSuccess("Rolling back: $class");
                    $object->down();
                    $this->output->printSuccess("Rolled back: $class");
                }
            }
        }
    }
}
