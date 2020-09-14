<?php

namespace App\Jobs;

use Midun\Queues\Queue;

class ExampleJob extends Queue
{
    public $email;
    public $name;

    public function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        \Log::info(__CLASS__);
    }
}
