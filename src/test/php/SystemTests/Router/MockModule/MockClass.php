<?php declare(strict_types = 1);

namespace Mys\SystemTests\Router\MockModule;

use Mys\Core\Api\HttpStatus\ServerExceptions\InternalServerErrorException;

class MockClass {
    /**
     * @throws InternalServerErrorException
     */
    public function internalServerError() {
        throw new InternalServerErrorException("expected exception");
    }

    /**
     * @return void
     */
    public function fatal(): void {
        $fatal = "";
        $fatal();
    }
}