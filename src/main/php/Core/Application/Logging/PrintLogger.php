<?php

namespace Mys\Core\Application\Logging;

use Exception;

class PrintLogger implements Logger
{

    public function error(Exception $errorClass): void
    {
        print_r("ERROR: " . $errorClass);
    }

    public function warning(string $warningMessage): void
    {
        print_r("WARNING: " . $warningMessage);
    }
}