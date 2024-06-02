<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class DummyModuleWithEndpointToken implements Module
{

    public function getClasses(): array
    {
        return [];
    }

    public function getModules(): array
    {
        return [];
    }

    public function getEndpoints(): array
    {
        $endpoint = new Endpoint(DummyComponent::class, "dummyFunction");
        $endpoint->setPath("/dummy");
        return [
            $endpoint,
        ];
    }
}