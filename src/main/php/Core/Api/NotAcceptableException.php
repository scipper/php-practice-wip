<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Exception;

class NotAcceptableException extends Exception
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct("NotAcceptableException");
    }
}