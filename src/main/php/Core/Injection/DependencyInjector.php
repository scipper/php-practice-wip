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
    private array $classList;

    /**
     * @var array
     */
    private array $instances;

    /**
     *
     */
    public function __construct() {
        $this->classList = [];
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
        if (isset($this->classList[$injectionToken])) {
            throw new ClassAlreadyRegisteredException($injectionToken);
        }

        $this->classList[$injectionToken] = $class ?: $injectionToken;
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
        if (array_key_exists($injectionToken, $this->instances)) {
            return $this->instances[$injectionToken];
        }

        if (in_array($injectionToken, $callChain)) {
            throw new CyclicDependencyDetectedException($callChain);
        }
        $callChain[] = $injectionToken;

        try {
            $dependencies = [];
            $reflector = new ReflectionClass($injectionToken);
            $constructor = $reflector->getConstructor();
            if ($constructor) {
                $reflectionParameters = $constructor->getParameters();
                foreach ($reflectionParameters as $parameter) {
                    $dependencies[] = $this->getWithCyclicDependencyDetection($parameter->getType()->getName(), $callChain);
                }
            }
        }
        catch (ReflectionException $e) {
            throw new ClassNotFoundException();
        }

        if (array_key_exists($injectionToken, $this->classList)) {
            $closureOrString = $this->classList[$injectionToken];
            if ($closureOrString instanceof Closure) {
                $instance = $closureOrString();
            } elseif (is_object($closureOrString)) {
                $instance = $closureOrString;
            } else {
                $instance = new $this->classList[$injectionToken](...$dependencies);
            }
        } else {
            $instance = new $injectionToken(...$dependencies);
        }

        $this->instances[$injectionToken] = $instance;

        return $instance;
    }
}