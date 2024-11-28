<?php declare(strict_types = 1);

namespace Mys\Core;

use Exception;
use Mys\Core\Logging\Logger;

class LoggerSpy implements Logger {

    private Exception $errorClass;

    private string $warningMessage;

    public function __construct() {}

    public function exception(Exception $errorClass): void {
        $this->errorClass = $errorClass;
    }

    public function exceptionWasCalledWith(): Exception {
        return $this->errorClass;
    }

    public function error(string $errorMessage): void {}

    public function warning(string $warningMessage): void {
        $this->warningMessage = $warningMessage;
    }

    public function warningWasCalledWith(): string {
        return $this->warningMessage;
    }

    public function info(string $infoMessage): void {}
}