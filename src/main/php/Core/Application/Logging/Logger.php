<?php

namespace Mys\Core\Application\Logging;

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