<?php

namespace Midun\Bus;

use DB;
use Midun\Queues\Queue;
use Midun\Contracts\Bus\Dispatcher as DispatcherContract;

class Dispatcher implements DispatcherContract
{
    /**
     * Dispatch a command to its appropriate handler.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function dispatch(Queue $job)
    {
        try {
            return DB::table('jobs')->insert([
                'queue' => str_replace('\\', '\\\\', get_class($job)),
                'payload' => json_encode($job->getSerializeData()),
                'attempts' => 0
            ]);
        } catch (\Exception $e) {
            throw new DispatcherException($e->getMessage());
        }
    }
}
