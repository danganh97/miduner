<?php

namespace Main\Contracts\Console;

interface Kernel
{
    /**
     * Get all of the commands registered with the console.
     *
     * @return array
     */
    public function all();

    /**
     * Get the output for the last run command.
     *
     * @return string
     */
    public function output();
}
