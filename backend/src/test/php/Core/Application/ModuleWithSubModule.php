<?php declare(strict_types=1);

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class ModuleWithSubModule extends Module
{
    public function getModules(): array
    {
        return [DummyModule::class, DummyModuleWithEndpointToken::class];
    }

    public function getEndpoints(): array
    {
        return [];
    }
}