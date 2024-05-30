<?php

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class DummyModule implements Module
{
    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return [DummyComponent::class];
    }
}