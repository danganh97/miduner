<?php

namespace Midun\Console\Commands\Queue;

use DB;
use Midun\Console\Command;

class QueueWorkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run queue jobs';

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
	 * @throws \ReflectionException
     */
    public function handle()
    {
        while (true) {
            sleep(1);
            $queue = DB::table('jobs')->orderBy('id', 'asc')->first();
            if (!$queue) {
                $this->output->print('Have no queue to execute.');
                continue;
            }

            $unserialize = (array) json_decode($queue->payload);

            $reflector = new \ReflectionClass($queue->queue);

            $constructor = $reflector->getConstructor();

            if (is_null($constructor)) {
                $job = new $queue->queue;
            } else {
                $parameters = $constructor->getParameters();

                $params = [];

                foreach ($parameters as $param) {
                    $params[] = $unserialize[$param->getName()];
                }
                $job = new $queue->queue(...$params);
            }

            $this->output->printSuccess("Processing: " . get_class($job));

            try {
                error_reporting(0);
                $job->handle();
                if (!is_null(error_get_last())) {
                    throw new \Exception(error_get_last()['message'] . ' in ' . error_get_last()['file'] . ' line ' . error_get_last()['line']);
                };
            } catch (\Exception $e) {
                DB::table('jobs')->where('id', $queue->id)->update(['attempts' => $queue->attempts + 1, 'last_error' => $e->getMessage()]);
                continue;
            }
            $this->output->printSuccess("Processed: " . get_class($job));

            DB::table('jobs')->where('id', $queue->id)->delete();
        }
    }
}
