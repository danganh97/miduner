<?php

namespace App\Console;

use App\Console\Commands\ExampleCommand;
use Midun\Console\Kernel as ConsoleKernel;
use Midun\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * List of commands
     * @var array $commands
     */
    protected array $commands = [
        ExampleCommand::class
    ];

    public function schedule(Schedule $schedule): void
    {
        $schedule->command(ExampleCommand::class)->daily();
        $schedule->command(ExampleCommand::class)->weekly();
        $schedule->command(ExampleCommand::class)->monthly();
        $schedule->command(ExampleCommand::class)->yearly();
        $schedule->command(ExampleCommand::class)->dailyAt('13:30');
        $schedule->command(ExampleCommand::class)->cron('* * * * *');
        $schedule->command(ExampleCommand::class)
            ->everyMinute()
            ->output(storage_path('logs/schedule.log'))
            ->cli('/usr/bin/php');
    }
}
