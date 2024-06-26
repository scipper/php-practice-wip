<?php declare(strict_types=1);

namespace Mys\Core;

use Exception;
use Mys\Core\Logging\Logger;

class LoggerSpy implements Logger
{

    private Exception $errorClass;

    private string $warningMessage;

    public function __construct()
    {
    }

    public function error(Exception $errorClass): void
    {
        $this->errorClass = $errorClass;
    }

    public function errorWasCalledWith(): Exception
    {
        return $this->errorClass;
    }

    public function warning(string $warningMessage): void
    {
        $this->warningMessage = $warningMessage;
    }

    public function warningWasCalledWith(): string
    {
        return $this->warningMessage;
    }

    public function info(string $infoMessage): void
    {

    }
}