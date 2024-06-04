<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Exception;

class MethodNotAllowedException extends Exception
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct("MethodNotAllowedException");
    }
}