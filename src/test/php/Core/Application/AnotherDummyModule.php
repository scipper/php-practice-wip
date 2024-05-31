<?php

namespace Mys\Core\Application;

use Mys\Core\Module\Module;

class AnotherDummyModule implements Module
{
    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return [AnotherDummyComponent::class];
    }
}