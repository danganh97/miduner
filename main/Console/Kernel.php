<?php

namespace Main\Console;

use Main\Contracts\Console\Kernel as KernelContract;

class Kernel implements KernelContract
{
    protected $commands = [];

    public function all()
    {
        return $this->commands;
    }

    public function output()
    {

    }
}