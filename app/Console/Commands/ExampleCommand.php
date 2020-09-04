<?php

namespace App\Console\Commands;

use Midun\Console\Command;

class ExampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:example';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Example command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \Log::info(__CLASS__);
    }
}
