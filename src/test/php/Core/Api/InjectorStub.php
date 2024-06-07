<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\Injection\Injector;

class InjectorStub implements Injector
{
    public function get(string $injectionToken): mixed
    {
        return new $injectionToken();
    }

    public function register(string $injectionToken, string $class = null): void
    {
    }

}