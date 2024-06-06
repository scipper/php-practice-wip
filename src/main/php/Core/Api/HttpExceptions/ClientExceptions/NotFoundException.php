<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpExceptions\ClientExceptions;

use Mys\Core\Api\HttpExceptions\HttpException;

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