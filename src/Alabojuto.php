<?php

namespace Odo;

use Swoole\Server;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Odo\Ojise\Message;
use Odo\Ojise\Payload;

class Alabojuto
{
    protected Server $server;
    protected Logger $log;

    public function __construct(
        protected array $services = [],
        protected int $port = 8000,
    )
    {
        $this->server = new Server("0.0.0.0", $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $this->setListeners();
        $this->log = new Logger('alabojuto');
        $this->log->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));
    }

    public function run(): void
    {
        $this->server->start();
    }

    private function setListeners(): void
    {
        $this->server->on('start', fn() => printf('Odo Alabojuto.' . PHP_EOL));
        $this->server->on(
            'receive', 
            fn(Server $server, int $fd, int $reactor_id, string $data) => $this->handleMessage($data, $server));
        $this->server->on('shutdown', fn() => printf("Tunu odo."));
    }

    /**
     * Handle the incoming message from client
     * @param string $data unserialized data
     * @param Server $server Server instance
     * @return void
     */
    protected function handleMessage(string $data, Server $server): void
    {
        $message = unserialize(serialize($data));
        if(!($message instanceof Message)) {
            $this->log->error('That data is not in the allowed structure');
        }
        switch($message->getAction()) {
        case Payload::ACTION_REGISTER:
            $this->registerService($message->getData());
            break;
        case Payload::ACTION_SPAWN:
            break;
        }
    }

    /**
     * Register a service in services pool
     * @param object $data the Payload data from message
     * @return void
     */
    protected function registerService(object $data): void
    {
        $this->services[] = $data->id;
    }
}
