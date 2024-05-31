<?php

namespace Mys\Core\Application;

use Mys\Core\Application\Logging\Logger;
use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\Injector;
use Mys\Core\LoggerSpy;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
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
     */
    public function test_logs_error_when_class_in_module_list_is_not_instance_of_module(): void
    {
        $moduleList = [DummyComponent::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy);

        $application->init();

        $this->assertInstanceOf(ClassIsNotModuleException::class, $this->loggerSpy->errorWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_logs_error_when_class_in_module_list_can_not_be_instantiated(): void
    {
        $moduleList = ["Invalid"];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy);

        $application->init();

        $this->assertInstanceOf(InvalidClassException::class, $this->loggerSpy->errorWasCalledWith());
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_registers_a_class_of_a_module(): void
    {
        $moduleList = [DummyModule::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy);

        $application->init();
        $injection = $this->injector->get(DummyComponent::class);

        $this->assertInstanceOf(DummyComponent::class, $injection);
    }
}
