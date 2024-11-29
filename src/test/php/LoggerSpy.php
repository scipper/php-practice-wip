<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Logging\Logger;
use Throwable;

class LoggerSpy implements Logger {

    private Throwable $errorClass;

    private string $warningMessage;

    public function __construct() {}

    public function exception(Throwable $errorClass): void {
        $this->errorClass = $errorClass;
    }

    public function exceptionWasCalledWith(): Throwable {
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