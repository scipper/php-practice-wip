<?php declare(strict_types=1);

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

    public function getModules(): array
    {
        return [];
    }

    public function getEndpoints(): array
    {
        return [];
    }
}