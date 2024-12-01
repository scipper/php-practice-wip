<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

use Closure;
use Mys\Core\ClassNotFoundException;
use ReflectionClass;
use ReflectionException;

class DependencyInjector implements Injector {
    /**
     * @var Closure|string[]
     */
    private array $registeredClasses;

    /**
     * @var array
     */
    private array $instances;

    /**
     *
     */
    public function __construct() {
        $this->registeredClasses = [];
        $this->instances = [];
    }

    /**
     *
     * @param string $injectionToken
     * @param string|object|null $class
     *
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function register(string $injectionToken, object|string $class = null): void {
        if (isset($this->registeredClasses[$injectionToken])) {
            throw new ClassAlreadyRegisteredException($injectionToken);
        }

        $this->registeredClasses[$injectionToken] = $class ?: $injectionToken;
    }

    /**
     * @param string $injectionToken
     *
     * @return mixed
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function get(string $injectionToken): mixed {
        return $this->getWithCyclicDependencyDetection($injectionToken);
    }

    /**
     * @param string $injectionToken
     * @param array $callChain
     *
     * @return mixed
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    private function getWithCyclicDependencyDetection(string $injectionToken, array $callChain = []): mixed {
        if (isset($this->instances[$injectionToken])) {
            return $this->instances[$injectionToken];
        }

        if (in_array($injectionToken, $callChain)) {
            throw new CyclicDependencyDetectedException($callChain);
        }
        $callChain[] = $injectionToken;
        $realClass = $injectionToken;
        if (isset($this->registeredClasses[$injectionToken])) {
            $realClass = $this->registeredClasses[$injectionToken];
        }

        try {
            $dependencies = [];
            $reflector = new ReflectionClass($realClass);
            $constructor = $reflector->getConstructor();
            if ($constructor) {
                $reflectionParameters = $constructor->getParameters();
                foreach ($reflectionParameters as $parameter) {
                    $parameterTypeName = $parameter->getType()->getName();
                    if ($parameterTypeName !== "string" && $parameterTypeName !== "int") {
                        $dependencies[] = $this->getWithCyclicDependencyDetection($parameterTypeName, $callChain);
                    }
                }
            }
        }
        catch (ReflectionException $e) {
            throw new ClassNotFoundException();
        }

        if (isset($this->registeredClasses[$injectionToken])) {
            $closureOrString = $this->registeredClasses[$injectionToken];
            if ($closureOrString instanceof Closure) {
                $instance = $closureOrString();
            } elseif (is_object($closureOrString)) {
                $instance = $closureOrString;
            } else {
                $instance = new $this->registeredClasses[$injectionToken](...$dependencies);
            }
        } else {
            $instance = new $injectionToken(...$dependencies);
        }

        $this->instances[$injectionToken] = $instance;

        return $instance;
    }
}