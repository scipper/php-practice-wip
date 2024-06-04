<?php declare(strict_types = 1);

namespace Mys\Core\Logging;

use Exception;

interface Logger
{
    /**
     * @param Exception $errorClass
     *
     * @return void
     */
    public function error(Exception $errorClass): void;

    /**
     * @param string $warningMessage
     *
     * @return void
     */
    public function warning(string $warningMessage): void;

    /**
     * @param string $infoMessage
     *
     * @return void
     */
    public function info(string $infoMessage): void;
}