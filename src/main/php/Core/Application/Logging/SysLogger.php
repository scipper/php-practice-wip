<?php

namespace Mys\Core\Application\Logging;

use Exception;

class SysLogger implements Logger
{

    public function error(Exception $errorClass): void
    {
        syslog(LOG_ERR, $errorClass);
    }

    public function warning(string $warningMessage): void
    {
        syslog(LOG_WARNING, $warningMessage);
    }
}