<?php declare(strict_types=1);

namespace Mys\Core\Injection;

use Mys\Core\ClassNotFoundException;
use Mys\Core\DummyClassWithDependency;
use Mys\Core\DummyDependency;
use Mys\Core\LoggerSpy;
use Mys\Core\Logging\Logger;
use PHPUnit\Framework\TestCase;

class DependencyInjectorTest extends TestCase
{

    /**
     * @var Logger
     */
    private Logger $loggerSpy;

    /**
     * @var Injector
     */
    private Injector $injector;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->loggerSpy = new LoggerSpy();
        $this->injector = new DependencyInjector($this->loggerSpy);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_throws_when_requested_class_is_not_registered(): void
    {
        $this->expectException(ClassNotFoundException::class);

        $this->injector->get(DummyClass::class);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_throws_when_requested_class_is_not_a_valid_class(): void
    {
        $this->expectException(ClassNotFoundException::class);

        $this->injector->register("InvalidClass");

        $this->injector->get("InvalidClass");
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_returns_instance_of_registered_class(): void
    {
        $this->injector->register(DummyClass::class);

        $result = $this->injector->get(DummyClass::class);

        $this->assertInstanceOf(DummyClass::class, $result);
    }

    /**
     * @return void
     */
    public function test_logs_warning_when_class_is_already_registered(): void
    {
        $this->injector->register(DummyClass::class);
        $this->injector->register(DummyClass::class);

        $this->assertEquals("Class is already registered: Mys\Core\Injection\DummyClass", $this->loggerSpy->warningWasCalledWith());
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_resolves_dependencies_of_class_when_requesting(): void
    {
        $this->injector->register(DummyClassWithDependency::class);
        $this->injector->register(DummyDependency::class);

        /**
         * @var DummyClassWithDependency $result
         */
        $result = $this->injector->get(DummyClassWithDependency::class);

        $this->assertInstanceOf(DummyDependency::class, $result->getDependency());
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_throws_when_a_cyclic_dependency_is_detected(): void
    {
        $this->expectException(CyclicDependencyDetectedException::class);

        $this->injector->register(CyclicClassA::class);
        $this->injector->register(CyclicClassB::class);
        $this->injector->get(CyclicClassA::class);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_map_an_interface_to_an_implementation(): void
    {
        $this->injector->register(DummyInterface::class, DummyClass::class);

        $injection = $this->injector->get(DummyInterface::class);

        $this->assertInstanceOf(DummyClass::class, $injection);
    }
}
