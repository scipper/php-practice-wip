<?php declare(strict_types = 1);

namespace Mys\CoreModules\Home;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class HomeModule implements Module
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
        return [];
    }

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array
    {
        return [];
    }
}