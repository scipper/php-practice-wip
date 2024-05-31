<?php declare(strict_types=1);

namespace Mys\Core\Application;

use Exception;

class InvalidClassException extends Exception
{
    public function __construct()
    {
        parent::__construct("InvalidClassException");
    }
}