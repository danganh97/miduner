<?php

namespace Midun\Console\Commands\View;

use Midun\Console\Command;
use Midun\Container;

class ViewClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear view cache and rewrite';

    /**
     * Flag is using cache
     * 
     * @var bool
     */
    protected $usingCache = true;

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
        $view = Container::getInstance()->make('view');
        $directory = $view->getDirectory();
        $cachingDirectory = $view->getCachingDirectory();

        if (is_dir($cachingDirectory)) {
            delete_directory($cachingDirectory);
            mkdir($cachingDirectory);
        }

        $views = items_in_folder($directory, false);

        foreach ($views as $v) {
            $view->makeCache($v);
        }

        $this->output->printSuccess("View cleared successfully.");
    }
}
