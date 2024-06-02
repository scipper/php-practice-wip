<?php declare(strict_types = 1);

namespace Mys\Core;

use Exception;
use Mys\Core\Logging\Logger;

class DummyLogger implements Logger
{
    public function error(Exception $errorClass): void
    {
    }

    public function warning(string $warningMessage): void
    {
    }

    public function info(string $infoMessage): void
    {
    }
}