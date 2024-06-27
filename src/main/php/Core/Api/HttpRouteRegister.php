<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Exception;
use Mys\Core\Api\HttpStatus\ClientExceptions\MethodNotAllowedException;
use Mys\Core\Api\HttpStatus\ClientExceptions\NotAcceptableException;
use Mys\Core\Api\HttpStatus\ClientExceptions\NotFoundException;
use Mys\Core\Api\HttpStatus\ClientExceptions\UnsupportedMediaTypeException;
use Mys\Core\Api\HttpStatus\HttpStatus;
use Mys\Core\Api\HttpStatus\ServerExceptions\InternalServerErrorException;
use Mys\Core\Api\HttpStatus\Success\NoContent;
use Mys\Core\Api\HttpStatus\Success\Ok;
use Mys\Core\Injection\Injector;
use Mys\Core\ParameterRecognition\ParameterRecognition;
use TypeError;

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
     */
    public function processRequest(Request $request): Response
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

        try
        {
            $payload = $this->parameterRecognition->recognise($injectionToken, $function, $request->getPayload());
            $class = $this->injector->get($injectionToken);
            $content = $class->{$function}(...$payload);

            if ($content === null)
            {
                return new Response(new NoContent(), $content, $endpoint->getProduces());
            }
            else
            {
                return new Response(new Ok(), $content, $endpoint->getProduces());
            }
        }
        catch (HttpStatus $exception)
        {
            return new Response($exception);
        }
        catch (Exception|TypeError $exception)
        {
            return new Response(new InternalServerErrorException($exception));
        }
    }
}