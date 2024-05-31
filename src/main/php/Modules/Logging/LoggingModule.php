<?php declare(strict_types=1);

namespace Mys\Modules\Logging;

use Mys\Core\Logging\Logger;
use Mys\Core\Logging\PrintLogger;
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