<?php declare(strict_types = 1);

namespace Mys\Core\Logging;

use Exception;

class SysLogger implements Logger
{
    /**
     * @param Exception $errorClass
     *
     * @return void
     */
    public function error(Exception $errorClass): void
    {
        syslog(LOG_ERR, $errorClass->getTraceAsString());
    }

    /**
     * @param string $warningMessage
     *
     * @return void
     */
    public function warning(string $warningMessage): void
    {
        syslog(LOG_WARNING, $warningMessage);
    }

    /**
     * @param string $infoMessage
     *
     * @return void
     */
    public function info(string $infoMessage): void
    {
        syslog(LOG_INFO, $infoMessage);
    }
}