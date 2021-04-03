<?php

namespace Odo\Listeners;

trait ServerListener
{
    protected function setListeners(): void
    {
        $this->server->on('start', fn() => printf("{$this->id} started" . PHP_EOL));
        $this->server->on('shutdown', fn() => printf("{$this->id} downed" . PHP_EOL));
    }
    protected function setReceiver(): void
    {
        $this->server->on('receive', fn() => printf("{$this->id} received packet" . PHP_EOL));
    }
}
