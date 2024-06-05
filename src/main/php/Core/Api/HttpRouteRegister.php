<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\Injector;
use Mys\Core\ParameterRecognition\FunctionNotFoundException;
use Mys\Core\ParameterRecognition\MissingPayloadException;
use Mys\Core\ParameterRecognition\ParameterRecognition;

class HttpRouteRegister implements RouteRegister
{
    /**
     * @var Endpoint[]
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
     * @param Request $request
     *
     * @return void
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MethodNotAllowedException
     * @throws MissingPayloadException
     * @throws NotAcceptableException
     * @throws NotFoundException
     */
    public function routeTo(Request $request): void
    {
        if (!array_key_exists($request->getPath(), $this->endpoints))
        {
            throw new NotFoundException();
        }

        $methods = $this->endpoints[$request->getPath()];
        if (!array_key_exists($request->getMethod(), $methods))
        {
            throw new MethodNotAllowedException();
        }

        /** @var Endpoint $endpoint */
        $endpoint = $methods[$request->getMethod()];

        if ($endpoint->getProduces() !== $request->getHeaders()["Accept"])
        {
            throw new NotAcceptableException();
        }

        $injectionToken = $endpoint->getClass();
        $function = $endpoint->getFunction();

        $payload = $this->parameterRecognition->recognise($injectionToken, $function, $request->getPayload());

        $class = $this->injector->get($injectionToken);
        $class->{$function}(...$payload);
    }
}