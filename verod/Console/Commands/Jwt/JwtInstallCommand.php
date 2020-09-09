<?php

namespace Midun\Console\Commands\Jwt;

use Midun\Console\Command;

class JwtInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Json web tokens management';

    /**
     * True format for command
     * 
     * @var string
     */
    protected $format = 'Please use jwt:install to install secret key for JWT token';

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
            if (strpos($value, 'JWT_SECRET') !== false) {
                $value = 'JWT_SECRET=' . generateRandomString(20);
            }
            fwrite($file, $value . "\n");
        }
        fclose($file);
        $this->output->printSuccess("Generated secret key for JWT (Json web tokens) successfully.");
    }
}
