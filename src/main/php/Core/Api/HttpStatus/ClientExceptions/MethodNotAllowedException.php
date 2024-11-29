<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus\ClientExceptions;

use Mys\Core\Api\HttpStatus\HttpException;

class MethodNotAllowedException extends HttpException
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(405, "Method Not Allowed", "Method Not Allowed Exception");
    }
}