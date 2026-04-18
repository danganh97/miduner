# Miduner Project Skeleton

This repository provides the official project skeleton for building applications with the **Miduner Framework**.

- Core framework package: [miduner/miduner](https://packagist.org/packages/miduner/miduner)
- Core framework repository: [github.com/miduner/framework](https://github.com/miduner/framework)

The project is designed for PHP 7.4+ and uses static typing features such as type hints and arrow functions.

## Requirements

- PHP `>= 7.4.10`
- PHP extensions:
  - `ext-pdo`
  - `ext-readline`
  - `ext-json`
- Composer

## Installation

Create a new Miduner project:

```bash
composer create-project danganh97/miduner:dev-master your-project-folder
```

## Quick Start

From the project root:

```bash
cp .env.example .env
php hustle key:generate
php hustle config:cache
php hustle serve
```

Run with a custom host and port:

```bash
php hustle serve --host=192.168.1.1 --port=1997
```

Use `--open` to open the application in your browser automatically.

## CLI Overview

Run the following command to view all available commands:

```bash
php hustle list
```

### Code Generation

```bash
php hustle make:command {CommandName}
php hustle make:controller {ControllerName}
php hustle make:model {ModelName}
php hustle make:request {RequestName}
php hustle make:migration --table={TableName}
```

### Configuration and Security

```bash
php hustle config:cache
php hustle key:generate
php hustle jwt:install
```

> After generating a new key or installing JWT, refresh configuration cache.

### Database Operations

```bash
php hustle migrate
php hustle migrate:rollback
php hustle db:seed
```

### Query and Routing Utilities

```bash
php hustle exec:query --query="select * from users"
php hustle exec:query --query="select * from users" --test=true
php hustle route:list
php hustle route:list --format=json
php hustle route:list --format=array
```

### Interactive Mode

```bash
php hustle live:code
```

For help on any command:

```bash
php hustle <command> --help
```

## Task Scheduling

Add the following entry to your crontab:

```bash
* * * * * cd miduner && php hustle schedule:run >> /dev/null 2>&1
```

Example scheduler setup in `App\Console\Kernel`:

```php
<?php

namespace App\Console;

use App\Console\Commands\ExampleCommand;
use Midun\Console\Kernel as ConsoleKernel;
use Midun\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
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
```

## Docker (Optional)

If PHP is not available locally, you can use Docker:

```bash
docker-compose up --build -d
```

Then add the following entry to your hosts file:

```text
127.0.0.1 miduner.local
```

## Documentation

Official documentation is available at: [miduner.com/docs](https://miduner.com/docs)

## Contributing

Contributions are welcome. To enable development mode:

```bash
php hustle development:enable
```

Or:

```bash
php hustle dev:mode
```

## Security

If you discover a security vulnerability, please email **danganh.dev@gmail.com**.

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).