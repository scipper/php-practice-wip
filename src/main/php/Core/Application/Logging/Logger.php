<?php

namespace Mys\Core\Application\Logging;

use Exception;

interface Logger
{
    public function error(Exception $errorClass): void;

    public function warning(string $warningMessage): void;
}