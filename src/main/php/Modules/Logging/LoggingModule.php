<?php declare(strict_types = 1);

namespace Mys\Modules\Logging;

use Mys\Core\Api\Endpoint;
use Mys\Core\Logging\Logger;
use Mys\Core\Logging\SysLogger;
use Mys\Core\Module\Module;

class LoggingModule implements Module
{

    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return [Logger::class => SysLogger::class];
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