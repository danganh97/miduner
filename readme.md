# Please read me mom-facker !!!

This shit is implemented `static typing` with type-hint and arrow function from `PHP 7.4` 

## Installation

```bash
   composer create-project danganh97/miduner:dev-master your-project-folder
```

## About Miduner Framework

Miduner Framework is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Miduner Framework attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

Miduner Framework is accessible, yet powerful, providing tools needed for large, robust applications. A superb combination of simplicity, elegance, and innovation give you tools you need to build any application with which you are tasked.

## Learning Miduner Framework

Miduner Framework has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Miduner Framework documentation](https://miduner.com/docs) is building.

## Contributing

Thank you for considering contributing to the Miduner Framework !

## Security Vulnerabilities

If you discover a security vulnerability within Miduner, please send an e-mail to [Dang Anh](https://facebook.com/underspected) from danganh.dev@gmail.com. All security vulnerabilities will be promptly addressed.

## Some features from Miduner Framework

*Require PHP Version >= `7.4.10`*

Let's run `php hustle list` to see all available supported commands. Here is some available feature.

**You're wanna making some things ?**

```bash
   php hustle make:command {Command name}
   php hustle make:controller {Controller name}
   php hustle make:model {Model name}
   php hustle make:request {Request name}
   php hustle make:migration --table={Table name}
```

**Or just wanna refresh caching ?**

```bash
   php hustle config:cache
```

**Generate application key !**

```bash
   php hustle key:generate
```

or install `Json Web Tokens` for the application ?

```bash
   php hustle jwt:install
```

>Then remember refresh caching to register new application key !

**Run migration ?** 
so easy

```bash
   php hustle migrate
```
or just rollback all of them

```bash
   php hustle migrate:rollback
```

**Let's run the seeder**

```bash
   php hustle db:seed
```

**Live run query, why not ?**

```bash
   php hustle exec:query --query="select * from users"
```
You just wanna make a test ? Ok please give --test=true, like:

```bash
   php hustle exec:query --query="select * from users" --test=true
```

**You don't know list of your defined route ?**
```bash
   php hustle route:list
```
Or view under ```json``` or ```array```
```bash
   php hustle route:list --format=json/array
```

**And of course, you can begin run live code with Miduner**
*Code with terminal like with a file*

```bash
   php hustle live:code
```

Aw shit ! I can't remember all that shit. Give helper

*Don't be worry, we're know that, please choose your command and give argument **--help** to get a cup of coffee*

>Here is example: ```php hustle serve --help```

## Task Scheduling

Just add to your crontab

`* * * * * cd miduner && php hustle schedule:run >> /dev/null 2>&1`

Example using in `App\Console\Kernel`

```php
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
        // Normal using
        $schedule->command(ExampleCommand::class)->daily();
        $schedule->command(ExampleCommand::class)->weekly();
        $schedule->command(ExampleCommand::class)->monthly();
        $schedule->command(ExampleCommand::class)->yearly();
        $schedule->command(ExampleCommand::class)->dailyAt('13:30');
        $schedule->command(ExampleCommand::class)->cron('* * * * *');

        // Run with custom output log and cli
        $schedule->command(ExampleCommand::class)
               ->everyMinute()
               ->output(storage_path('logs/schedule.log'))
               ->cli('/usr/bin/php'); 
    }
}
```

## How to start ?

```bash
cp .env.example .env
   php hustle key:generate
   php hustle config:cache
   php hustle serve
```
or run with ip and port custom

```bash
   php hustle serve --host=192.168.1.1 --port=1997
```
*Note: you can using argument --open to open it up on browser*

>Now your app is running at [127.0.0.1:8000](127.0.0.1:8000)

**F*ck i don't install php on my local**

Okay, got it.

If the php is not installed on your local. Don't worry just follow my pants

```bash
   docker build ./docker
   docker-compose up -d
```
   or only this shit if you're a lazy guy
```bash
   docker-compose up --build -d
```

>Now, add **127.0.0.1 &emsp; miduner.local** to your **/etc/hosts**

If you have no idea for this step, please google search for setup virtual host.

And still many things can't be write down here. Please leave a message if you want to take this


## License

The Miduner Framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
*(Just a kidding)*

So, this is the fake framework, please use or not and don't facking leave a blame

If you wanna become contributor, let's run:

```bash
   php hustle development:enable
```
