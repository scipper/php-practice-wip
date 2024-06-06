<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\Api\HttpExceptions\ClientExceptions\MethodNotAllowedException;
use Mys\Core\Api\HttpExceptions\ClientExceptions\NotAcceptableException;
use Mys\Core\Api\HttpExceptions\ClientExceptions\NotFoundException;
use Mys\Core\Api\HttpExceptions\ClientExceptions\UnsupportedMediaTypeException;
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
     * @return Response
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function routeTo(Request $request): Response
    {
        if (!array_key_exists($request->getPath(), $this->endpoints))
        {
            return new Response(new NotFoundException());
        }

        $methods = $this->endpoints[$request->getPath()];
        if (!array_key_exists($request->getMethod(), $methods))
        {
            return new Response(new MethodNotAllowedException());
        }

        /** @var Endpoint $endpoint */
        $endpoint = $methods[$request->getMethod()];

        if ($endpoint->getProduces() !== $request->getHeaders()["accept"])
        {
            return new Response(new NotAcceptableException());
        }
        if ($endpoint->getConsumes() !== $request->getHeaders()["content-type"])
        {
            return new Response(new UnsupportedMediaTypeException());
        }

        $injectionToken = $endpoint->getClass();
        $function = $endpoint->getFunction();

        $payload = $this->parameterRecognition->recognise($injectionToken, $function, $request->getPayload());

        $class = $this->injector->get($injectionToken);
        $class->{$function}(...$payload);

        return new Response();
    }
}