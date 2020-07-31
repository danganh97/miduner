<?php

namespace Main\Console\Commands;

use Main\Console\Command;

class MigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migration database tables';

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
        $type = $this->getType();

        switch (true) {
            case is_null($type) || empty($type):
                return $this->execMigrate();
            case $type == 'rollback':
                return $this->execRollback();
        }
    }

    public function execRollback()
    {
        $files = scandir(BASE . '/database/migration', 1);
        arsort($files);
        foreach ($files as $file) {
            if (strlen($file) > 5) {
                include BASE . '/database/migration/' . $file;
                $classes = get_declared_classes();
                $class = end($classes);
                $object = new $class;
                if (method_exists($object, 'down')) {
                    $this->colors->printSuccess("Rolling back: $class");
                    $object->down();
                    $this->colors->printSuccess("Rolled back: $class");
                }
            }
        }
    }

    public function execMigrate()
    {
        foreach (scandir(BASE . '/database/migration', 1) as $file) {
            if (strlen($file) > 5) {
                include BASE . '/database/migration/' . $file;
                $classes = get_declared_classes();
                $class = end($classes);
                $object = new $class;
                if (method_exists($object, 'up')) {
                    $this->colors->printSuccess("Migrating: $class");
                    $object->up();
                    $this->colors->printSuccess("Migrated: $class");
                }
            }
        }
    }
}
