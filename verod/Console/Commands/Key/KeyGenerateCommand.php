<?php

namespace Midun\Console\Commands\Key;

use Midun\Console\Command;

class KeyGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate application key';

    /**
     * Flag check using cache
     * @var boolean
     */
    protected $usingCache = false;

    /**
     * Other called signatures
     */
    protected $otherSignatures = [
        'key:',
        'key:gen',
        'key:genera',
        'gen:key',
        'generate:key'
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
        $env = base_path('.env');
        $file_contents = file_get_contents($env);
        $each = explode("\n", $file_contents);
        $file = fopen($env, 'w');
        for ($i = 0; $i <= count($each) - 1; $i++) {
            if ($i == count($each) - 1) {
                if (strlen($each[$i]) <= 0) {
                    continue;
                }
            }
            $value = $each[$i];
            if (strpos($value, 'APP_KEY') !== false) {
                $value = 'APP_KEY=' . password_hash(microtime(true), PASSWORD_BCRYPT);
            }
            fwrite($file, $value . "\n");
        }
        fclose($file);
        $this->output->printSuccess("Generate key successfully.");
    }
}
