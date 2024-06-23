<?php declare(strict_types = 1);

namespace Mys\Modules\Ping;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class PingModule implements Module
{

    /**
     * @inheritDoc
     */
    public function getClasses(): array
    {
        return [PingComponent::class];
    }

    /**
     * @inheritDoc
     */
    public function getModules(): array
    {
        return [];
    }

    public function getEndpoints(): array
    {
        $pingStringEndpoint = new Endpoint(PingComponent::class, "pingString");
        $pingStringEndpoint->setPath("/ping");
        $pingStringEndpoint->setMethod("post");

        $pingPongEndpoint = new Endpoint(PingComponent::class, "ping");
        $pingPongEndpoint->setPath("/ping");

//        $pingObjectEndpoint = new Endpoint(PingComponent::class, "pingObject");
//        $pingObjectEndpoint->setPath("/pingObject");
//        $pingObjectEndpoint->setMethod("post");
        return [$pingStringEndpoint, $pingPongEndpoint];
    }
}