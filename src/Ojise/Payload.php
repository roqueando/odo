<?php

namespace Odo\Ojise;

class Payload
{
    const ACTION_REGISTER = 'register';
    const ACTION_SPAWN = 'spawn';

    public function __construct(
        protected string $action,
        protected object $data
    ) {}
}
