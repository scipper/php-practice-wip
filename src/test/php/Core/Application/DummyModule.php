<?php declare(strict_types=1);

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

    /**
     * @return string[]
     */
    public function getModules(): array
    {
        return [];
    }

    public function getEndpoints(): array
    {
        return [];
    }
}