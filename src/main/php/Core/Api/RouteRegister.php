<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\Injector;
use Mys\Core\ParameterRecognition\ParameterRecognition;

class RouteRegister
{
    /**
     * @var Endpoint[] $endpoints
     */
    private array $endpoints;

    /**
     * @var ParameterRecognition
     */
    private ParameterRecognition $parameterRecognition;

    /**
     * @var Injector
     */
    private Injector $injector;

    /**
     * @param ParameterRecognition $parameterRecognition
     * @param Injector $injector
     */
    public function __construct(ParameterRecognition $parameterRecognition, Injector $injector)
    {
        $this->endpoints = [];
        $this->parameterRecognition = $parameterRecognition;
        $this->injector = $injector;
    }

    /**
     * @param Endpoint $endpoint
     *
     * @return void
     */
    public function registerEndpoint(Endpoint $endpoint): void
    {
        if (!array_key_exists($endpoint->getPath(), $this->endpoints))
        {
            $this->endpoints[$endpoint->getPath()] = [];
        }
        $this->endpoints[$endpoint->getPath()][$endpoint->getMethod()] = $endpoint;
    }

    /**
     * @param string $path
     * @param string $method
     * @param string|null $rawPayload
     *
     * @return void
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function routeTo(string $path, string $method = "get", string $rawPayload = null): void
    {
        $normalPath = strtolower($path);
        $normalMethod = strtolower($method);
        if (!array_key_exists($normalPath, $this->endpoints))
        {
            throw new NotFoundException();
        }

        $methods = $this->endpoints[$normalPath];
        if (!array_key_exists($normalMethod, $methods))
        {
            throw new MethodNotAllowedException();
        }

        $endpoint = $methods[$normalMethod];
        $injectionToken = $endpoint->getClass();
        $function = $endpoint->getFunction();

        $payload = $this->parameterRecognition->recognise($injectionToken, $function, $rawPayload);

        $class = $this->injector->get($injectionToken);
        $class->{$function}(...$payload);
    }
}