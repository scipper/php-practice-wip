<?php

namespace Mys\Core\Injection;

use Mys\Core\Application\Logging\Logger;
use ReflectionClass;
use ReflectionException;

class DependencyInjector implements Injector
{
    /**
     * @var string[] $classList
     */
    private array $classList;

    /**
     * @var Logger $logger
     */
    private Logger $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->classList = [];
        $this->logger = $logger;
    }

    /**
     *
     * @param string      $injectionToken
     * @param string|null $class
     *
     * @return void
     */
    public function register(string $injectionToken, string $class = null): void
    {
        if (in_array($injectionToken, $this->classList))
        {
            $this->logger->warning("Class is already registered: $injectionToken");
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
    public function get(string $injectionToken): mixed
    {
        return $this->getWithCyclicDependencyDetection($injectionToken);
    }

    /**
     * @param string $injectionToken
     * @param array  $callChain
     *
     * @return mixed
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    private function getWithCyclicDependencyDetection(string $injectionToken, array $callChain = []): mixed
    {
        if (!array_key_exists($injectionToken, $this->classList))
        {
            throw new ClassNotFoundException();
        }

        if (in_array($injectionToken, $callChain))
        {
            throw new CyclicDependencyDetectedException($callChain);
        }
        $callChain[] = $injectionToken;

        try
        {
            $dependencies = [];
            $reflector = new ReflectionClass($injectionToken);
            $constructor = $reflector->getConstructor();
            if ($constructor)
            {
                $reflectionParameters = $constructor->getParameters();
                foreach ($reflectionParameters as $parameter)
                {
                    $dependencies[] = $this->getWithCyclicDependencyDetection($parameter->getType()->getName(), $callChain);
                }
            }
        }
        catch (ReflectionException $e)
        {
            throw new ClassNotFoundException();
        }

        return new $this->classList[$injectionToken](...$dependencies);
    }
}