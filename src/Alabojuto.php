<?php

namespace Odo;

use Swoole\Server;

class Alabojuto
{
    protected Server $server;
    public function __construct(
        protected array $processes = [],
        protected int $port = 8000,
    )
    {
        $this->server = new Server("0.0.0.0", $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $this->setListeners();
    }

    public function run(): void
    {
        $this->server->start();
    }

    private function setListeners(): void
    {
        $this->server->on('start', fn() => printf('Odo Alabojuto.' . PHP_EOL));
        $this->server->on('receive', fn(Server $server, int $fd, int $reactor_id, string $data) => printf($data));
        $this->server->on('shutdown', fn() => printf("Tunu odo."));
    }
}
