<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Mys\LoggerSpy;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase {

    /**
     * @var RouteRegisterSpy
     */
    private RouteRegisterSpy $routeRegister;

    /**
     * @var LoggerSpy
     */
    private LoggerSpy $loggerSpy;

    /**
     * @var InjectorSpy
     */
    private InjectorSpy $injectorSpy;

    /**
     * @return void
     */
    public function setUp(): void {
        $this->loggerSpy = new LoggerSpy();
        $this->routeRegister = new RouteRegisterSpy();
        $this->injectorSpy = new InjectorSpy();
    }

    /**
     * @return void
     */
    public function test_logs_error_when_class_in_module_list_is_not_instance_of_module(): void {
        $moduleList = [DummyComponent::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister, $this->injectorSpy);

        $application->init();

        $this->assertInstanceOf(ClassIsNotModuleException::class, $this->loggerSpy->exceptionWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_logs_error_when_class_in_module_list_can_not_be_instantiated(): void {
        $moduleList = ["Invalid"];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister, $this->injectorSpy);

        $application->init();

        $this->assertInstanceOf(InvalidClassException::class, $this->loggerSpy->exceptionWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_an_endpoint_defined_in_a_module(): void {
        $moduleList = [DummyModuleWithEndpointToken::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister, $this->injectorSpy);

        $application->init();

        $this->assertSame([DummyComponent::class, "dummyFunction", "/dummy", "get"], $this->routeRegister->registerEndpointWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_an_endpoint_defined_in_a_sub_module(): void {
        $moduleList = [ModuleWithSubModule::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister, $this->injectorSpy);

        $application->init();

        $this->assertSame([DummyComponent::class, "dummyFunction", "/dummy", "get"], $this->routeRegister->registerEndpointWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_a_service_to_injector(): void {
        $moduleList = [ServicesModule::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister, $this->injectorSpy);

        $application->init();

        $this->assertEquals(
            ["token" => StubService::class, "class" => StubService::class],
            $this->injectorSpy->registerWasCalledWith()
        );
    }

    /**
     * @return void
     */
    public function test_registers_a_service_to_injector_with_token(): void {
        $moduleList = [ServicesWithTokenModule::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister, $this->injectorSpy);

        $application->init();

        $this->assertEquals(
            ["token" => DummyInterface::class, "class" => StubService::class],
            $this->injectorSpy->registerWasCalledWith()
        );
    }
}
