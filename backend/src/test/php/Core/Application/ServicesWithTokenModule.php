<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class ServicesWithTokenModule extends Module {
    public function getServices(): array {
        return [
            DummyInterface::class => StubService::class,
        ];
    }

    public function getEndpoints(): array {
        return [];
    }
}