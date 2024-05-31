<?php

namespace Mys\Core\Injection;

use Exception;

class ClassNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("ClassNotFoundException");
    }
}