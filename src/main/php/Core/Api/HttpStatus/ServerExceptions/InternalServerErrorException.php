<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus\ServerExceptions;

use Exception;
use Mys\Core\Api\HttpStatus\HttpException;
use TypeError;

class InternalServerErrorException extends HttpException
{
    /**
     *
     */
    public function __construct(Exception|TypeError $exception = null)
    {
        parent::__construct(500, "Internal Server Error", $exception);
    }
}