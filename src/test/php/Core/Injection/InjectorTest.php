<?php

namespace Mys\Core\Injection;

use PHPUnit\Framework\TestCase;
use ReflectionException;

/*
 * throws when requested class is not registered
 * returns instance of registered class
 * resolves dependencies of class when requesting
 * generates an instance of a class only once
 */

class InjectorTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function test_throws_when_requested_class_is_not_registered()
    {
        $this->expectException(ClassNotFoundException::class);

        $injector = new Injector();

        $injector->get(DummyClass::class);
    }

    /**
     * @throws ClassNotFoundException
     * @throws ClassAlreadyRegisteredException
     * @throws ReflectionException
     */
    public function test_returns_instance_of_registered_class()
    {
        $injector = new Injector();
        $injector->register(DummyClass::class);

        $result = $injector->get(DummyClass::class);

        $this->assertInstanceOf(DummyClass::class, $result);
    }

    public function test_class_can_only_be_registered_once()
    {
        $this->expectException(ClassAlreadyRegisteredException::class);

        $injector = new Injector();

        $injector->register(DummyClass::class);
        $injector->register(DummyClass::class);
    }

    /**
     * @throws ClassNotFoundException
     * @throws ClassAlreadyRegisteredException
     * @throws ReflectionException
     */
    public function test_resolves_dependencies_of_class_when_requesting()
    {
        $injector = new Injector();

        $injector->register(DummyClassWithDependency::class);
        $injector->register(DummyDependency::class);

        /**
         * @var DummyClassWithDependency $result
         */
        $result = $injector->get(DummyClassWithDependency::class);

        $this->assertInstanceOf(DummyDependency::class, $result->getDependency());
    }
}
