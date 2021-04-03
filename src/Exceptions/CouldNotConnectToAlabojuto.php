<?php

namespace Odo\Exceptions;

use Exception;

class CouldNotConnectToAlabojuto extends Exception
{
    public function __construct($message = "Could not connect to Alabojuto (Supervisor)")
    {
        parent::__construct($message); 
    }
}
