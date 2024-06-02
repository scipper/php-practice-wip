<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\Injection\Injector;

class InjectorSpy implements Injector
{
    private string $getCalled;

    public function get(string $injectionToken): mixed
    {
        return $this->getCalled = $injectionToken;
    }

    public function register(string $injectionToken, string $class = null): void
    {
    }

    public function getWasCalledWith()
    {
        return $this->getCalled;
    }
}