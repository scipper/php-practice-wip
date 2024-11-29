<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class ServicesModule extends Module {
    public function getServices(): array {
        return [
            StubService::class,
        ];
    }

    public function getEndpoints(): array {
        return [];
    }
}