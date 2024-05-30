<?php

namespace Mys\Core\Injection;

use ReflectionClass;
use ReflectionException;

class Injector
{
    /**
     * @var string[] $classList
     */
    private array $classList;

    public function __construct()
    {
        $this->classList = [];
    }

    /**
     * @param string $class
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function register(string $class)
    {
        if (in_array($class, $this->classList)) {
            throw new ClassAlreadyRegisteredException();
        }

        $this->classList[$class] = $class;
    }

    /**
     * @param string $class
     * @return mixed
     * @throws ClassNotFoundException
     * @throws ReflectionException
     */
    public function get(string $class)
    {
        if (!in_array($class, $this->classList, true)) {
            throw new ClassNotFoundException();
        }

        $dependencies = [];
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();
        if($constructor) {
            $reflectionParameters = $constructor->getParameters();
            foreach ($reflectionParameters AS $parameter) {
                $dependencies[] = $this->get($parameter->getType()->getName());
            }
        }

        return new $this->classList[$class](...$dependencies);
    }
}