<?php declare(strict_types = 1);

namespace Mys\SystemTests\Router\MockModule;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class MockModule implements Module {

    /**
     * @return string[]
     */
    public function getClasses(): array {
        return [];
    }

    /**
     * @return string[]
     */
    public function getModules(): array {
        return [];
    }

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array {
        $internalServerError = new Endpoint(MockClass::class, "internalServerError");
        $internalServerError->setPath("/internalServerError");

        $classNotFound = new Endpoint("ClassNotHere", "");
        $classNotFound->setPath("/classNotFound");

        $fatal = new Endpoint(MockClass::class, "fatal");
        $fatal->setPath("/fatal");

        return [$internalServerError, $classNotFound, $fatal];
    }
}