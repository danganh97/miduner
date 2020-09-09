<?php

namespace Midun\Contracts\Bus;

interface Dispatcher
{
    /**
     * Dispatch a command to its appropriate handler.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function dispatch(\Midun\Queues\Queue $job);
}