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
        return [$pingStringEndpoint];
    }
}