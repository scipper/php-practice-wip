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
     */
    public function get(string $class)
    {
        if (!in_array($class, $this->classList, true)) {
            throw new ClassNotFoundException();
        }

        try {
            $dependencies = [];
            $reflector = new ReflectionClass($class);
            $constructor = $reflector->getConstructor();
            if ($constructor) {
                $reflectionParameters = $constructor->getParameters();
                foreach ($reflectionParameters as $parameter) {
                    $dependencies[] = $this->get($parameter->getType()->getName());
                }
            }
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException();
        }

        return new $this->classList[$class](...$dependencies);
    }
}