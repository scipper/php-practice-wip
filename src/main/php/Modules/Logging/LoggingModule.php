<?php declare(strict_types = 1);

namespace Mys\Modules\Logging;

use Mys\Core\Logging\Logger;
use Mys\Core\Logging\PrintLogger;
use Mys\Core\Module\Module;

class LoggingModule implements Module
{

    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return [Logger::class => PrintLogger::class];
    }

    /**
     * @return string[]
     */
    public function getModules(): array
    {
        return [];
    }
}