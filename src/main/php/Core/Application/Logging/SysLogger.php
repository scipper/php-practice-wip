<?php

namespace Mys\Core\Application\Logging;

use Exception;

class SysLogger implements Logger
{

    public function error(Exception $errorClass)
    {
        syslog(LOG_ERR, $errorClass);
    }

    public function warning(string $warningMessage)
    {
        syslog(LOG_WARNING, $warningMessage);
    }
}