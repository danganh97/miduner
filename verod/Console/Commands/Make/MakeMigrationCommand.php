<?php

namespace Midun\Console\Commands\Make;

use Midun\Console\Command;

class MakeMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migration';

    /**
     * The console migration description.
     *
     * @var string
     */
    protected $description = 'Making migration service';

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
        $table = $this->getOptions('table');

        if(!$table || empty($table) || is_null($table)) {
            $this->output->printError("You're missing argument 'table'. Please using correct format.\n>> make:migration --table={table_name}");
            exit(1);
        }

        $defaultMigratePath = base_path('midun/Helpers/Init/migrate.txt');
        $defaultMigrate = file_get_contents($defaultMigratePath);
        $defaultMigrate = str_replace(':table', $table, $defaultMigrate);
        $defaultMigrate = str_replace(':Table', ucfirst($table), $defaultMigrate);
        $fullDir = database_path('migration/');
        $date = date('Ymd_His');
        $name = "{$date}_{$table}_migration.php";
        $needleTable = "{$fullDir}$name";
        if (!file_exists($needleTable)) {
            $myfile = fopen($needleTable, "w") or die("Unable to open file!");
            fwrite($myfile, $defaultMigrate);
            fclose($myfile);
            $this->output->printSuccess("Created migration {$name}");
        } else {
            $this->output->printWarning("Table {$needleTable} already exists");
        }
    }
}
