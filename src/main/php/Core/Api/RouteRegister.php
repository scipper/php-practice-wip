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

    public function routeTo(Request $request): void;
}