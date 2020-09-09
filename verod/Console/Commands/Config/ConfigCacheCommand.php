<?php

namespace Midun\Console\Commands\Config;

use Midun\Console\Command;
use Midun\Console\Commands\View\ViewClearCommand;
use Midun\Console\ConsoleException;
use Midun\Console\Kernel;

class ConfigCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear and rewrite caching, views, config';

    /**
     * Flag check using cache
     * @var boolean
     */
    protected $usingCache = false;

    /**
     * Other called signatures
     */
    protected $otherSignatures = [
        'c:c'
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
	 *
	 * @throws ConsoleException
     */
    public function handle()
    {
        (new ConfigClearCommand)->handle();

        (new Kernel)->handle([
            (new ViewClearCommand)->getSignature()
        ]);
    }
}
