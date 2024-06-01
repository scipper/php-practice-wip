<?php declare(strict_types=1);

namespace Mys\Core\Logging;

use Exception;

class PrintLogger implements Logger
{

    /**
     * @param Exception $errorClass
     *
     * @return void
     */
    public function error(Exception $errorClass): void
    {
        print_r("ERROR: " . $errorClass);
    }

    /**
     * @param string $warningMessage
     *
     * @return void
     */
    public function warning(string $warningMessage): void
    {
        print_r("WARNING: $warningMessage\n");
    }

    /**
     * @param string $infoMessage
     *
     * @return void
     */
    public function info(string $infoMessage): void
    {
        print_r("INFO: $infoMessage\n");
    }
}