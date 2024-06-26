<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Mys\Core\Api\Endpoint;
use Mys\Core\Api\Request;
use Mys\Core\Api\Response;
use Mys\Core\Api\RouteRegister;

class RouteRegisterSpy implements RouteRegister
{
    private array $registerCalled;

    public function __construct()
    {
        $this->registerCalled = [];
    }

    public function registerEndpoint(Endpoint $endpoint): void
    {
        $this->registerCalled = [$endpoint->getClass(), $endpoint->getFunction(), $endpoint->getPath(), $endpoint->getMethod()];
    }

    public function processRequest(Request $request): Response
    {
        return new Response();
    }

    public function registerEndpointWasCalledWith()
    {
        return $this->registerCalled;
    }
}