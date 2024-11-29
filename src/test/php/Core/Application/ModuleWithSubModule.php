<?php declare(strict_types=1);

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class ModuleWithSubModule implements Module
{
    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getModules(): array
    {
        return [DummyModule::class, DummyModuleWithEndpointToken::class];
    }

    public function getEndpoints(): array
    {
        return [];
    }
}