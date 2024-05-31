<?php

namespace Mys\Modules\Welcome;

use Mys\Core\Module\Module;

class WelcomeModule implements Module
{
    public function getClasses(): array
    {
        return [WelcomeComponent::class];
    }

    public function getModules(): array
    {
        return [];
    }
}