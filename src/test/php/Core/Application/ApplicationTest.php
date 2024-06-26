<?php declare(strict_types=1);

namespace Mys\Core\Application;

use Mys\Core\LoggerSpy;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{

    /**
     * @var LoggerSpy
     */
    private LoggerSpy $loggerSpy;

    /**
     * @var InjectorSpy
     */
    private InjectorSpy $injector;

    /**
     * @var RouteRegisterSpy
     */
    private RouteRegisterSpy $routeRegister;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->loggerSpy = new LoggerSpy();
        $this->injector = new InjectorSpy();
        $this->routeRegister = new RouteRegisterSpy();
    }

    /**
     * @return void
     */
    public function test_logs_error_when_class_in_module_list_is_not_instance_of_module(): void
    {
        $moduleList = [DummyComponent::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy, $this->routeRegister);

        $application->init();

        $this->assertInstanceOf(ClassIsNotModuleException::class, $this->loggerSpy->errorWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_logs_error_when_class_in_module_list_can_not_be_instantiated(): void
    {
        $moduleList = ["Invalid"];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy, $this->routeRegister);

        $application->init();

        $this->assertInstanceOf(InvalidClassException::class, $this->loggerSpy->errorWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_a_class_of_a_module(): void
    {
        $moduleList = [DummyModule::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy, $this->routeRegister);

        $application->init();

        $this->assertEquals([DummyComponent::class, DummyComponent::class], $this->injector->registerWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_multiple_classes_of_a_module(): void
    {
        $moduleList = [DummyModule::class, AnotherDummyModule::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy, $this->routeRegister);

        $application->init();

        $this->assertEquals(2, $this->injector->registerWasCalledTimes());
    }

    /**
     * @return void
     */
    public function test_registers_a_class_of_a_sub_module(): void
    {
        $moduleList = [ModuleWithSubModule::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy, $this->routeRegister);

        $application->init();

        $this->assertEquals([DummyComponent::class, DummyComponent::class], $this->injector->registerWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_a_class_with_an_interface_as_injection_token(): void
    {
        $moduleList = [DummyModuleWithInjectionToken::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy, $this->routeRegister);

        $application->init();

        $this->assertEquals([DummyInterface::class, DummyComponent::class], $this->injector->registerWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_an_endpoint_defined_in_a_module(): void
    {
        $moduleList = [DummyModuleWithEndpointToken::class];
        $application = new Application($this->injector, $moduleList, $this->loggerSpy, $this->routeRegister);

        $application->init();

        $this->assertSame([DummyComponent::class, "dummyFunction", "/dummy", "get"], $this->routeRegister->registerEndpointWasCalledWith());
    }
}
