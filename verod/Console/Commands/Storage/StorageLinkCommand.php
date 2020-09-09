<?php

namespace Midun\Console\Commands\Storage;

use Midun\Console\Command;

class StorageLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link storage to public';

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
        if (file_exists(public_path('storage'))) {
        	$this->output->printError('The "public/storage" directory already exists.');
        	exit(1);
        }

        $this->app->make('fileSystem')->link(
            storage_path('app/public'),
            public_path('storage')
        );

        $this->output->printSuccess('The [public/storage] directory has been linked.');
    }
}
