<?php

namespace Mys\Core\Application\Logging;

use Exception;

class PrintLogger implements Logger
{

    public function error(Exception $errorClass)
    {
        print_r("ERROR: " . $errorClass);
    }

    public function warning(string $warningMessage)
    {
        print_r("WARNING: " . $warningMessage);
    }
}