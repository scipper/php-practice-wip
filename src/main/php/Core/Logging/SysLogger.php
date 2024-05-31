<?php declare(strict_types=1);

namespace Mys\Core\Logging;

use Exception;

class SysLogger implements Logger
{

    public function error(Exception $errorClass): void
    {
        syslog(LOG_ERR, $errorClass->getTraceAsString());
    }

    public function warning(string $warningMessage): void
    {
        syslog(LOG_WARNING, $warningMessage);
    }

    public function info(string $infoMessage): void
    {
        syslog(LOG_INFO, $infoMessage);
    }
}