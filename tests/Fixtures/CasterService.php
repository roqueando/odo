<?php

namespace Tests\Fixtures;

use Odo\Iyansile;

class CasterService extends Iyansile
{
    public function __construct()
    {
        parent::__construct(3030, Iyansile::TYPE_DATAGRAM);
        $this->setPacketReceiver(fn($s, $data) => $this->handleMessage($data));
    }

    private function handleMessage(string $data): void
    {
        printf("Receiving and handling $data");
    }
}
