<?php

namespace Mys\Core\Application;

use Exception;
use Mys\Core\Injection\Injector;

class InjectorSpy implements Injector
{
    private string $registeredClass;

    private int $registerCall;

    public function __construct()
    {
        $this->registerCall = 0;
    }

    public function get(string $injectionToken): mixed
    {
        throw new Exception("Not implemented");
    }

    public function register(string $injectionToken, string $class = null): void
    {
        $this->registeredClass = $injectionToken;
        $this->registerCall++;
    }

    public function registerWasCalledWith(): string
    {
        return $this->registeredClass;
    }

    public function registerWasCalledTimes(): int
    {
        return $this->registerCall;
    }
}