<?php declare(strict_types=1);

namespace Mys\Core\Module;

use Mys\Core\Api\Endpoint;

interface Module
{
    /**
     * @return string[]
     */
    public function getClasses(): array;

    /**
     * @return string[]
     */
    public function getModules(): array;

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array;
}