<?php

namespace Tests\Feature;

use Tests\Idanwo;

class RunningServicesTest extends Idanwo
{
    const TEST_SERVICE = "Tests\\Fixtures\\CasterService";
    public function setUp(): void
    {
        parent::setUp();
        $this->isAlabojutonRunning();
        $this->isServicesRunning(self::TEST_SERVICE);
    }

    /** @test **/
    public function should_have_services_running()
    {
        sleep(1);
        $this->shouldSeeServiceOutput(self::TEST_SERVICE, "started");
    }

    /** @test **/
    public function should_return_error_log_if_send_wrong_message()
    {
        $this->sendMessage("testing");
        $this->assertStringContainsString("That data is not in the allowed structure", file_get_contents('tests/.output/alabojuto.log'));
    }

    private function sendMessage(string $message): void
    {
        $sock = fsockopen('0.0.0.0', 8000);
        fwrite($sock, $message);
        sleep(1);
    }
}
