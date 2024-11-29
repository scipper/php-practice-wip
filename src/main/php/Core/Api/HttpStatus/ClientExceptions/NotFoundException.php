<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus\ClientExceptions;

use Mys\Core\Api\HttpStatus\HttpException;

class NotFoundException extends HttpException
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(404, "Not Found");
    }
}