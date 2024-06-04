<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\ClassNotFoundException;
use Mys\Core\DummyClassWithDependency;
use Mys\Core\DummyDependency;
use Mys\Core\DummyLogger;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Injection\Injector;
use Mys\Core\ParameterRecognition\ParameterRecognition;
use PHPUnit\Framework\TestCase;

class RouteRegisterTest extends TestCase
{
    /**
     * @var RouteRegister
     */
    private RouteRegister $routeRegister;

    /**
     * @var Injector
     */
    private Injector $injector;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $logger = new DummyLogger();
        $this->injector = new DependencyInjector($logger);
        $this->injector->register(DummyApi::class);
        $parameterRecognition = new ParameterRecognition();
        $this->routeRegister = new HttpRouteRegister($parameterRecognition, $this->injector);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_call_class_function_from_path()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path");

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_case_of_registered_path_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/PaTh");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path");

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_case_of_calling_path_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/PaTh");

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_throw_when_path_is_not_found()
    {
        $this->expectException(NotFoundException::class);

        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path");
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_throw_when_path_is_called_with_wrong_method()
    {
        $this->expectException(MethodNotAllowedException::class);

        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $endpoint->setMethod("POST");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path");
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_case_of_registered_method_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathPost");
        $endpoint->setPath("/path");
        $endpoint->setMethod("PoSt");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path", "post");

        $this->assertTrue(DummyApi::$pathPostWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_case_of_calling_method_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathPost");
        $endpoint->setPath("/path");
        $endpoint->setMethod("post");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path", "PoSt");

        $this->assertTrue(DummyApi::$pathPostWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_registers_two_methods_to_one_path()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathPost");
        $endpoint->setPath("/path");
        $endpoint->setMethod("post");
        $this->routeRegister->registerEndpoint($endpoint);
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $endpoint->setMethod("get");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path", "get");
        $this->routeRegister->routeTo("/path", "post");

        $this->assertTrue(DummyApi::$pathPostWasCalled);
        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function test_parses_raw_payload_to_correct_function_parameter()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathParam");
        $endpoint->setPath("/path");
        $endpoint->setMethod("post");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path", "post", "1");

        $this->assertTrue(DummyApi::$pathParamWasCalledCorrectly);
    }

    /**
     * @return void
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function test_call_class_with_dependencies()
    {
        $this->injector->register(DummyDependency::class);
        $this->injector->register(DummyClassWithDependency::class);
        $endpoint = new Endpoint(DummyClassWithDependency::class, "getDependency");
        $endpoint->setPath("/path");
        $endpoint->setMethod("get");
        $this->routeRegister->registerEndpoint($endpoint);

        $this->routeRegister->routeTo("/path");

        $this->assertTrue(DummyClassWithDependency::$getDependencyWasCalled);
    }
}
