<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

use Mys\Core\ClassNotFoundException;
use ReflectionClass;
use ReflectionException;

class DependencyInjector implements Injector {
    /**
     * @var string[]
     */
    private array $classList;

    /**
     *
     */
    public function __construct() {
        $this->classList = [];
    }

    /**
     *
     * @param string $injectionToken
     * @param string|null $class
     *
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function register(string $injectionToken, string $class = null): void {
        if (in_array($injectionToken, $this->classList)) {
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
            return new $this->classList[$injectionToken](...$dependencies);
        }
        else {
            return new $injectionToken(...$dependencies);
        }
    }
}