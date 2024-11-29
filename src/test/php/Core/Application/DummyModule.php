<?php declare(strict_types=1);

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class DummyModule extends Module {
    public function getEndpoints(): array
    {
        return [];
    }
}