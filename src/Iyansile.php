<?php

namespace Odo;

use Swoole\Server;
use Odo\Listeners\ServerListener;

abstract class Iyansile
{
    use ServerListener;

    const TYPE_DATAGRAM = 'udp';
    const TYPE_TCP = 'tcp';
    const TYPE_HTTP = 'http';

    private string $id;
    protected Server $server;

    public function __construct(protected int $port, protected string $type) 
    {
        $this->id = md5(uniqid() . time());
        $this->port = $port;

        switch($this->type) {
        case self::TYPE_DATAGRAM:
            $this->server = new Server('0.0.0.0', $this->port, SWOOLE_BASE, SWOOLE_SOCK_UDP);
            $this->setListeners();
            break;
        case self::TYPE_TCP:
            $this->server = new Server('0.0.0.0', $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
            $this->setListeners();
            break;
        case self::TYPE_HTTP:
            // soon
            break;
        }
    }

    /**
     * Run the server on desired port and type
     * @return void
     */
    public function run(): void
    {
        $this->server->start();
    }

    /**
     * Set the receiver for tcp or udp
     * @param callable $handler callback function for handle receiver
     * @return void
     */
    public function setReceiver(callable $handler): void
    {
        if($this->type != self::TYPE_HTTP) {
            $this->server->on('receive', $handler);
        }
    }

    /**
     * Return Iyansile id
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
