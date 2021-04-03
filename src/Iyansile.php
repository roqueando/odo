<?php

namespace Odo;

use Exception;
use Odo\Exceptions\CouldNotConnectToAlabojuto;
use Swoole\Server;
use Swoole\Client;
use Odo\Listeners\ServerListener;
use Odo\Ojise\Message;
use Odo\Ojise\Payload;

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
        $this->registerOnSupervisor();
        $this->server->start();
    }

    /**
     * Set the receiver packet for tcp or udp
     * @param callable $handler callback function for handle receiver
     * @return void
     */
    public function setPacketReceiver(callable $handler): void
    {
        if($this->type != self::TYPE_HTTP) {
            $this->server->on('packet', $handler);
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

    /**
     * Register service on Alabojuto service pool
     * @throws CouldNotConnectToAlabojuto
     */
    protected function registerOnSupervisor(): void
    {
        $client = new Client(SWOOLE_SOCK_TCP);
        if(!$client->connect('0.0.0.0', 8000)) {
            throw new CouldNotConnectToAlabojuto();
        }
        $payload = $this->createRegisterPayload([
            'id' => $this->getId()
        ]);
        $client->send(serialize(new Message($payload)));
    }

    private function createRegisterPayload(array $data): Payload
    {
        return new Payload(Payload::ACTION_REGISTER, (object) $data);
    }
}
