<?php declare(strict_types = 1);

namespace Mys\Logging;

use Exception;
use Mys\Core\Logging\Clock;
use Mys\Core\Logging\Logger;

use function error_log;

class SysLogger implements Logger {

    /**
     * @var string
     */
    private string $logsFolder;

    /**
     * @var string
     */
    private string $logFileName;

    /**
     * @var Clock
     */
    private Clock $clock;

    /**
     * @param string $logsFolder
     * @param Clock $clock
     */
    public function __construct(string $logsFolder, Clock $clock) {
        $this->logsFolder = $logsFolder;
        $this->clock = $clock;

        if (!is_dir($this->logsFolder)) {
            mkdir($this->logsFolder, 0777, true);
        }
        $this->logFileName = "/application.log";
        touch($this->logsFolder . $this->logFileName);
    }

    /**
     * @param Exception $errorClass
     *
     * @return void
     */
    public function exception(Exception $errorClass): void {
        $this->error($errorClass->getMessage());
        foreach (explode("\n", $errorClass->getTraceAsString()) as $item) {
            $this->log("", $item);
        }
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function error(string $errorMessage): void {
        $type = "ERROR: ";
        $this->log($type, $errorMessage);
    }

    /**
     * @param string $warningMessage
     *
     * @return void
     */
    public function warning(string $warningMessage): void {
        $type = "WARNING: ";
        $this->log($type, $warningMessage);
    }

    /**
     * @param string $infoMessage
     *
     * @return void
     */
    public function info(string $infoMessage): void {
        $type = "INFO: ";
        $this->log($type, $infoMessage);
    }

    /**
     * @param string $type
     * @param string $infoMessage
     *
     * @return void
     */
    private function log(string $type, string $infoMessage): void {
        $message = $this->clock->microtime() . " | $type" . $infoMessage . "\n";
        error_log($message, 3, $this->logsFolder . $this->logFileName);
    }
}