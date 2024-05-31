<?php

namespace Mys\Core\Injection;

use Mys\Core\Application\Logging\Logger;
use ReflectionClass;
use ReflectionException;

class Injector
{
    /**
     * @var string[] $classList
     */
    private array $classList;

    /**
     * @var Logger $logger
     */
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->classList = [];
        $this->logger = $logger;
    }

    /**
     * @param string $class
     * @return void
     */
    public function register(string $class)
    {
        if (in_array($class, $this->classList)) {
            $this->logger->warning("Class is already registered");
        }

        $this->classList[$class] = $class;
    }

    /**
     * @param string $class
     * @return mixed
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function get(string $class)
    {
        return $this->getWithCyclicDependencyDetection($class);
    }

    /**
     * @param string $class
     * @param array $callChain
     * @return mixed
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    private function getWithCyclicDependencyDetection(string $class, array $callChain = [])
    {
        if (!in_array($class, $this->classList, true)) {
            throw new ClassNotFoundException();
        }

        if(in_array($class, $callChain)) {
            throw new CyclicDependencyDetectedException($callChain);
        }
        $callChain[] = $class;

        try {
            $dependencies = [];
            $reflector = new ReflectionClass($class);
            $constructor = $reflector->getConstructor();
            if ($constructor) {
                $reflectionParameters = $constructor->getParameters();
                foreach ($reflectionParameters as $parameter) {
                    $dependencies[] = $this->getWithCyclicDependencyDetection($parameter->getType()->getName(), $callChain);
                }
            }
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException();
        }

        return new $this->classList[$class](...$dependencies);
    }
}