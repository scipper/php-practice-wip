<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Closure;
use Mys\Core\Injection\Injector;

class InjectorSpy implements Injector {
    private array $registeredClass;

    public function get(string $injectionToken): mixed {
        return null;
    }

    public function register(string $injectionToken, Closure|string $class = null): void {
        $this->registeredClass = ["token" => $injectionToken, "class" => $class];
    }

    public function registerWasCalledWith(): array {
        return $this->registeredClass;
    }
}