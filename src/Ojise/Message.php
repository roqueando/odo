<?php

namespace Odo\Ojise;

class Message
{
    public function __construct(protected Payload $payload)
    {}

    public function getAction(): string
    {
        return $this->payload->action;
    }
    
    public function getData(): object
    {
        return $this->payload->data;
    }
}
