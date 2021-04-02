<?php

namespace Odo\Console;

use Odo\Console\Commands\{
    Run,
    Spawn
};
use Symfony\Component\Console\Application as ConsoleApplication;

class Application
{
    const VERSION = '0.0.2';

    protected $app;

    public function __construct()
    {
        $this->app = new ConsoleApplication("Odo", self::VERSION);
        $this->register([
            Run::class,
            Spawn::class
        ]);
    }

    public function run()
    {
        $this->app->run();
    }

    /**
     * Register commands
     * @param string[] $commands
     * @return void
     */
    protected function register(array $commands): void
    {
        foreach($commands as $command) {
            $this->app->add(new $command);
        }
    }
}
