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
     * @return void
     */
    public function setUp(): void {
        $this->loggerSpy = new LoggerSpy();
        $this->routeRegister = new RouteRegisterSpy();
    }

    /**
     * @return void
     */
    public function test_logs_error_when_class_in_module_list_is_not_instance_of_module(): void {
        $moduleList = [DummyComponent::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister);

        $application->init();

        $this->assertInstanceOf(ClassIsNotModuleException::class, $this->loggerSpy->exceptionWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_logs_error_when_class_in_module_list_can_not_be_instantiated(): void {
        $moduleList = ["Invalid"];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister);

        $application->init();

        $this->assertInstanceOf(InvalidClassException::class, $this->loggerSpy->exceptionWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_an_endpoint_defined_in_a_module(): void {
        $moduleList = [DummyModuleWithEndpointToken::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister);

        $application->init();

        $this->assertSame([DummyComponent::class, "dummyFunction", "/dummy", "get"], $this->routeRegister->registerEndpointWasCalledWith());
    }

    /**
     * @return void
     */
    public function test_registers_an_endpoint_defined_in_a_sub_module(): void {
        $moduleList = [ModuleWithSubModule::class];
        $application = new Application($this->loggerSpy, $moduleList, $this->routeRegister);

        $application->init();

        $this->assertSame([DummyComponent::class, "dummyFunction", "/dummy", "get"], $this->routeRegister->registerEndpointWasCalledWith());
    }
}
