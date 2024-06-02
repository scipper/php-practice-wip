<?php declare(strict_types = 1);

namespace Mys\Modules\Welcome;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class WelcomeModule implements Module
{
    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return [WelcomeComponent::class];
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
        $welcomeEndpoint = new Endpoint(WelcomeComponent::class, "printWelcomeMessage");
        $welcomeEndpoint->setPath("/");
        $welcomeEndpoint->setMethod("get");
        return [$welcomeEndpoint];
    }
}