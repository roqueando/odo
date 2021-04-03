<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class Idanwo extends TestCase
{
    public static $testOutput;
    public $alabojutoPID;
    public $runningServices;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        $this->stopAlabojuto();
        $this->stopServices();
    }

    protected function isAlabojutonRunning(): void
    {
        $this->alabojutoPID = exec(
            'php odo run > tests/.output/alabojuto.log 2>&1 & echo $!',
            $output
        );
        sleep(1);
    }

    protected function isServicesRunning(string ...$services): void
    {
        foreach($services as $service) {
            $serviceLogName = str_replace('\\', '_', $service);
            $service = str_replace('\\', '\\\\', $service);
            $pid = exec("php odo spawn $service > tests/.output/$serviceLogName.log 2>&1 & echo $!");

            $this->runningServices[$pid] = $service;
        }
    }

    protected function shouldSeeServiceOutput($service, $content)
    {
        $serviceLogName = str_replace('\\', '_', $service);

        $this->assertStringContainsString(
            $content, 
            file_get_contents("tests/.output/$serviceLogName.log"),
            "Couldn find '$content' in '$serviceLogName' output"
        );
    }

    protected function stopAlabojuto(): void
    {
        exec("kill {$this->alabojutoPID} > /dev/null 2>&1");
    }

    protected function stopServices(): void
    {
        // FIXME: change $pid => $service to $service and use key($service)
        // to return the key (PID)
        foreach($this->runningServices as $pid => $service) {
            exec("kill $pid > /dev/null 2>&1");
        }
    }
}
