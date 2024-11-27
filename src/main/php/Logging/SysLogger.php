<?php declare(strict_types = 1);

namespace Mys\Logging;

use Exception;
use Mys\Core\Logging\Logger;

use function error_log;

class SysLogger implements Logger
{
    /**
     * @param Exception $errorClass
     *
     * @return void
     */
    public function error(Exception $errorClass): void
    {
        error_log($errorClass->getTraceAsString());
    }

    /**
     * @param string $warningMessage
     *
     * @return void
     */
    public function warning(string $warningMessage): void
    {
        error_log($warningMessage);
    }

    /**
     * @param string $infoMessage
     *
     * @return void
     */
    public function info(string $infoMessage): void
    {
        error_log($infoMessage);
    }
}