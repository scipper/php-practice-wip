<?php declare(strict_types=1);

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