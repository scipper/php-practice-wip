<?php

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class DummyModuleWithInjectionToken implements Module
{

    public function getClasses(): array
    {
        return [DummyInterface::class => DummyComponent::class];
    }

    public function getModules(): array
    {
        return [];
    }
}