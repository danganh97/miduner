<?php

namespace Midun\Console\Commands;

use Midun\Console\Command;

class CreateServerCliCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating cli server';

    /**
     * Other called signatures
     */
    protected $otherSignatures = [
        'ser',
        'serv',
        'server',
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
        $host = '127.0.0.1';
        $port = '8000';
        $open = false;
        foreach ($this->argv() as $param) {
            if (strpos($param, '-h=') !== false || strpos($param, '--host=') !== false) {
                $host = str_replace('--host=', '', $param);
            }
            if (strpos($param, '-p=') !== false || strpos($param, '--port=') !== false) {
                $port = str_replace('--port=', '', $param);
            }
            if (strpos($param, '-o') !== false || strpos($param, '--open') !== false) {
                $open = true;
            }
        }
        $this->output->printSuccess("Starting development at: http://{$host}:{$port} \nUsing argument --open to open server on browser.");
        if ($open) {
            exec("open " . "http://{$host}:{$port}");
        }
        system("php -S {$host}:{$port} server.php");
    }
}
