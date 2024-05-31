<?php

namespace Mys\Modules\Test;

use Mys\Core\Module\Module;

class TestModule implements Module
{
    public function getClasses(): array
    {
        return [
            TestComponent::class,
            TestService::class,
            TestBService::class,
        ];
    }
}