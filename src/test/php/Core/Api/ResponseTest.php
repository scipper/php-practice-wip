<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\ParameterRecognition\ParameterRecognition;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @var RouteRegister
     */
    private RouteRegister $routeRegister;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $injector = new InjectorStub();
        $injector->register(DummyApi::class);
        $parameterRecognition = new ParameterRecognition();
        $this->routeRegister = new HttpRouteRegister($parameterRecognition, $injector);
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);
    }

    /**
     * @return void
     */
    public function test_returns_a_response_object()
    {
        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return void
     */
    public function test_response_has_status_code_200()
    {
        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Ok", $response->getStatusText());
        $this->assertEquals(null, $response->getErrorMessage());
    }

    /**
     * @return void
     */
    public function test_response_contains_result_of_component_function_execution()
    {
        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("content", $response->getContent());
    }

    /**
     * @return void
     */
    public function test_converts_int_response_to_string()
    {
        $endpoint = new Endpoint(DummyApi::class, "getNumber");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("1", $response->getContent());
    }

    /**
     * @return void
     */
    public function test_converts_double_response_to_string()
    {
        $endpoint = new Endpoint(DummyApi::class, "getDouble");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("1.1", $response->getContent());
    }

    /**
     * @return void
     */
    public function test_converts_bool_response_to_string()
    {
        $endpoint = new Endpoint(DummyApi::class, "getBool");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("false", $response->getContent());
    }

    /**
     * @return void
     */
    public function test_converts_array_response_to_string()
    {
        $endpoint = new Endpoint(DummyApi::class, "getArray");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("[\"value\"]", $response->getContent());
    }

    /**
     * @return void
     */
    public function test_converts_object_with_public_properties_response_to_string()
    {
        $endpoint = new Endpoint(DummyApi::class, "getObject");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("{\"firstName\":\"FirstTest\",\"lastName\":\"LastTest\"}", $response->getContent());
    }

    /**
     * @return void
     */
    public function test_response_has_status_code_204_when_content_is_empty()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathPost");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals("No Content", $response->getStatusText());
        $this->assertEquals(null, $response->getErrorMessage());
    }

    /**
     * @return void
     */
    public function test_response_is_error_of_not_found_when_path_is_not_found()
    {
        $request = new Request("/pathMissing");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not Found", $response->getStatusText());
    }

    /**
     * @return void
     */
    public function test_response_is_error_of_method_not_allowed_when_path_is_called_with_wrong_method()
    {
        $request = new Request("/path");
        $request->setMethod("POST");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals("Method Not Allowed", $response->getStatusText());
    }

    /**
     * @return void
     */
    public function test_response_is_error_of_not_acceptable_when_requesting_a_response_format_but_the_endpoint_produces_something_else()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathParam");
        $endpoint->setPath("/path");
        $endpoint->setProduces("text/plain");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(406, $response->getStatusCode());
        $this->assertEquals("Not Acceptable", $response->getStatusText());
    }

    /**
     * @return void
     */
    public function test_response_is_error_of_unsupported_media_type()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathParam");
        $endpoint->setPath("/path");
        $endpoint->setConsumes("text/plain");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setHeader("Content-Type", "application/json");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(415, $response->getStatusCode());
        $this->assertEquals("Unsupported Media Type", $response->getStatusText());
    }

    /**
     * @return void
     */
    public function test_response_contains_expected_error_from_controller_function()
    {
        $endpoint = new Endpoint(DummyApi::class, "throwsException");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("Internal Server Error", $response->getStatusText());
    }

    /**
     * @return void
     */
    public function test_unknown_exceptions_from_controller_are_mapped_to_internal_server_error()
    {
        $endpoint = new Endpoint(DummyApi::class, "unhandledException");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("Internal Server Error", $response->getStatusText());
        $this->assertEquals("Something went wrong", $response->getErrorMessage());
    }

    /**
     * @return void
     */
    public function test_maps_cyclic_dependency_exception_to_internal_server_error()
    {
        $endpoint = new Endpoint(DummyCyclicDependencyComponent::class, "path");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("Internal Server Error", $response->getStatusText());
        $this->assertEquals("CyclicDependencyDetectedException: ClassA -> ClassB", $response->getErrorMessage());
    }

    /**
     * @return void
     */
    public function test_maps_class_not_found_exception_to_internal_server_error()
    {
        $endpoint = new Endpoint("MissingClass", "path");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("Internal Server Error", $response->getStatusText());
        $this->assertEquals("ClassNotFoundException", $response->getErrorMessage());
    }

    /**
     * @return void
     */
    public function test_maps_type_error_to_internal_server_error()
    {
        $endpoint = new Endpoint(DummyApi::class, "typeError");
        $endpoint->setPath("/path");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("Internal Server Error", $response->getStatusText());
        $this->assertEquals("Something typed wrong", $response->getErrorMessage());
    }

    /**
     * @return void
     */
    public function test_response_contains_content_type_header_based_on_endpoint_produces_value()
    {
        $endpoint = new Endpoint(DummyApi::class, "pathGet");
        $endpoint->setPath("/path");
        $endpoint->setProduces("text/plain");
        $this->routeRegister->registerEndpoint($endpoint);

        $request = new Request("/path");
        $request->setHeader("Accept", "text/plain");
        $response = $this->routeRegister->processRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("text/plain", $response->getContentType());
    }
}
