<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;

interface RouteRegister
{
    /**
     * @param Endpoint $endpoint
     *
     * @return void
     */
    public function registerEndpoint(Endpoint $endpoint): void;

    /**
     * @param string $path
     * @param string $method
     * @param string|null $rawPayload
     *
     * @return void
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function routeTo(string $path, string $method = "get", string $rawPayload = null): void;
}