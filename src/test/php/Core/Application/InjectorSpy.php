<?php declare(strict_types=1);

namespace Mys\Core\Application;

use Exception;
use Mys\Core\Injection\Injector;

class InjectorSpy implements Injector
{
    /**
     * @var string[] $registeredClass
     */
    private array $registeredClass;

    /**
     * @var int $registerCall
     */
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
        $this->registeredClass = [$injectionToken, $class];
        $this->registerCall++;
    }

    /**
     * @return string[]
     */
    public function registerWasCalledWith(): array
    {
        return $this->registeredClass;
    }

    /**
     * @return int
     */
    public function registerWasCalledTimes(): int
    {
        return $this->registerCall;
    }
}