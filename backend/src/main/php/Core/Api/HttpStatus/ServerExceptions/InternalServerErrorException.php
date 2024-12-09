<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus\ServerExceptions;

use Mys\Core\Api\HttpStatus\HttpException;
use Throwable;

class InternalServerErrorException extends HttpException {
    /**
     * @param string $errorMessage
     * @param Throwable|null $exception
     */
    public function __construct(string $errorMessage, Throwable $exception = null) {
        parent::__construct(500, "Internal Server Error", $errorMessage, $exception);
    }
}