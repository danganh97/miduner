<?php

namespace Midun\Contracts\Console;

interface Command
{
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle();
}
