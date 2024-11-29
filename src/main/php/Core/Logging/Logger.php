<?php declare(strict_types = 1);

namespace Mys\Core\Logging;

use Throwable;

interface Logger
{
    /**
     * @param Throwable $errorClass
     *
     * @return void
     */
    public function exception(Throwable $errorClass): void;

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function error(string $errorMessage): void;

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