<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus\ClientExceptions;

use Mys\Core\Api\HttpStatus\HttpException;

class NotAcceptableException extends HttpException
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(406, "Not Acceptable", "Not Acceptable Exception");
    }
}