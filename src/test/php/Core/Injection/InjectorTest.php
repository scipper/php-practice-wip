<?php

namespace Mys\Core\Injection;

use Mys\Core\Application\Logging\Logger;
use Mys\Core\LoggerSpy;
use PHPUnit\Framework\TestCase;

class InjectorTest extends TestCase
{

    /**
     * @var Logger $loggerSpy
     */
    private Logger $loggerSpy;

    /**
     * @var Injector $injector
     */
    private Injector $injector;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->loggerSpy = new LoggerSpy();
        $this->injector = new Injector($this->loggerSpy);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     */
    public function test_throws_when_requested_class_is_not_registered()
    {
        $this->expectException(ClassNotFoundException::class);

        $this->injector->get(DummyClass::class);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     */
    public function test_returns_instance_of_registered_class()
    {
        $this->injector->register(DummyClass::class);

        $result = $this->injector->get(DummyClass::class);

        $this->assertInstanceOf(DummyClass::class, $result);
    }

    /**
     * @return void
     */
    public function test_logs_warning_when_class_is_already_registered()
    {
        $this->injector->register(DummyClass::class);
        $this->injector->register(DummyClass::class);

        $this->assertEquals("Class is already registered", $this->loggerSpy->warningWasCalledWith());
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     */
    public function test_resolves_dependencies_of_class_when_requesting()
    {
        $this->injector->register(DummyClassWithDependency::class);
        $this->injector->register(DummyDependency::class);

        /**
         * @var DummyClassWithDependency $result
         */
        $result = $this->injector->get(DummyClassWithDependency::class);

        $this->assertInstanceOf(DummyDependency::class, $result->getDependency());
    }
}
