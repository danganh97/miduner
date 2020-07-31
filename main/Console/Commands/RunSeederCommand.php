<?php

namespace Main\Console\Commands;

use App\Models\User;
use Main\Console\Command;

class RunSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run seeding data database';

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
        $this->colors->printSuccess("Seeded successfully.");
    }
}
