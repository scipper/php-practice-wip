<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpExceptions\ClientExceptions;

use Mys\Core\Api\HttpExceptions\HttpException;

class NotAcceptableException extends HttpException
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(406, "Not Acceptable");
    }
}