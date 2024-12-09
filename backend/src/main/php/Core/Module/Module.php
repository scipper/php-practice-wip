<?php declare(strict_types = 1);

namespace Mys\Core\Module;

use Mys\Core\Api\Endpoint;

abstract class Module
{
    /**
     * @return string[]
     */
    public function getServices(): array {
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
    public abstract function getEndpoints(): array;
}