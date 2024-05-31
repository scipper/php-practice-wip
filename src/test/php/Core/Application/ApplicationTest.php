<?php

namespace Mys\Core\Application;

use Mys\Core\Application\Logging\Logger;
use Mys\Core\LoggerSpy;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{

    /**
     * @var Logger $loggerSpy
     */
    private Logger $loggerSpy;

    /**
     * @var InjectorSpy $injector
     */
    private InjectorSpy $injector;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->loggerSpy = new LoggerSpy();
        $this->injector = new InjectorSpy();
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
     */
    public function test_registers_a_class_of_a_module(): void
    {
        $moduleList = [DummyModule::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy);

        $application->init();

        $this->assertEquals(DummyComponent::class, $this->injector->registerWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_multiple_classes_of_a_module(): void
    {
        $moduleList = [DummyModule::class, AnotherDummyModule::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy);

        $application->init();

        $this->assertEquals(2, $this->injector->registerWasCalledTimes());
    }
}
