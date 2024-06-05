<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\ClassNotFoundException;
use Mys\Core\DummyClassWithDependency;
use Mys\Core\DummyDependency;
use Mys\Core\DummyLogger;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Injection\Injector;
use Mys\Core\ParameterRecognition\FunctionNotFoundException;
use Mys\Core\ParameterRecognition\MissingPayloadException;
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
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_call_class_function_from_path()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_registered_path_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/PaTh");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_calling_path_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/PaTh");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_throw_when_path_is_not_found()
    {
        $this->expectException(NotFoundException::class);

        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_throw_when_path_is_called_with_wrong_method()
    {
        $this->expectException(MethodNotAllowedException::class);

        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $endpoint->setMethod("POST");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_registered_method_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathPost");
        $endpoint->setPath("/path");
        $endpoint->setMethod("PoSt");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setMethod("post");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathPostWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_calling_method_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathPost");
        $endpoint->setPath("/path");
        $endpoint->setMethod("post");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setMethod("PoSt");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathPostWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
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

        $request1 = new Request("/path");
        $this->routeRegister->routeTo($request1);
        $request2 = new Request("/path");
        $request2->setMethod("post");
        $this->routeRegister->routeTo($request2);

        $this->assertTrue(DummyApi::$pathPostWasCalled);
        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_parses_raw_payload_to_correct_function_parameter()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathParam");
        $endpoint->setPath("/path");
        $endpoint->setMethod("post");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setMethod("post");
        $request->setPayload("1");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathParamWasCalledCorrectly);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_call_class_with_dependencies()
    {
        $this->injector->register(DummyDependency::class);
        $this->injector->register(DummyClassWithDependency::class);
        $endpoint = new Endpoint(DummyClassWithDependency::class, "getDependency");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyClassWithDependency::$getDependencyWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_throws_when_requesting_a_response_format_but_the_endpoint_produces_something_else()
    {
        $this->expectException(NotAcceptableException::class);

        $endpoint = new Endpoint(DummyApi::class, "pathParam");
        $endpoint->setPath("/path");
        $endpoint->setProduces("text/plain");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_produces_of_endpoint_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $endpoint->setProduces("AppLicAtion/JSOn");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_consumes_of_endpoint_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $endpoint->setConsumes("AppLicAtion/JSOn");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_header_value_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setHeader("Accept", "AppLicAtion/JSOn");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_case_of_header_key_gets_normalised()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $endpoint->setConsumes("text/plain");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setHeader("ContEnt-TyPE", "text/plain");
        $this->routeRegister->routeTo($request);

        $this->assertTrue(DummyApi::$pathGetWasCalled);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws UnsupportedMediaTypeException
     */
    public function test_throws_when_sending_wrong_content_type()
    {
        $this->expectException(UnsupportedMediaTypeException::class);

        $endpoint = new Endpoint(DummyApi::class, "pathParam");
        $endpoint->setPath("/path");
        $endpoint->setConsumes("text/plain");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setHeader("Content-Type", "application/json");
        $this->routeRegister->routeTo($request);
    }
}
