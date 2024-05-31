<?php

namespace Mys\Modules\Logging;

use Mys\Core\Application\Logging\Logger;
use Mys\Core\Application\Logging\PrintLogger;
use Mys\Core\Module\Module;

class LoggingModule implements Module
{

    public function getClasses(): array
    {
        return [Logger::class => PrintLogger::class];
    }

    public function getModules(): array
    {
        return [];
    }
}