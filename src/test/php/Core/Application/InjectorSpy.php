<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Closure;
use Mys\Core\Injection\Injector;
use Mys\Core\LoggerSpy;

class InjectorSpy implements Injector {
    /**
     * @var string[] $registeredClass
     */
    private array $registeredClass;

    /**
     * @var int $registerCall
     */
    private int $registerCall;

    /**
     * @var LoggerSpy
     */
    private LoggerSpy $loggerSpy;

    public function __construct(LoggerSpy $loggerSpy) {
        $this->registerCall = 0;
        $this->loggerSpy = $loggerSpy;
    }

    /**
     * @param string $injectionToken
     *
     * @return LoggerSpy
     */
    public function get(string $injectionToken): LoggerSpy {
        return $this->loggerSpy;
    }

    public function register(string $injectionToken, Closure|string $class = null): void {
        $this->registeredClass = [$injectionToken, $class];
        $this->registerCall++;
    }

    /**
     * @return string[]
     */
    public function registerWasCalledWith(): array {
        return $this->registeredClass;
    }

    /**
     * @return int
     */
    public function registerWasCalledTimes(): int {
        return $this->registerCall;
    }
}