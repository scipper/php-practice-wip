<?php declare(strict_types = 1);

namespace Mys\Core\Api;

interface RouteRegister
{
    /**
     * @param Endpoint $endpoint
     *
     * @return void
     */
    public function registerEndpoint(Endpoint $endpoint): void;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function processRequest(Request $request): Response;
}