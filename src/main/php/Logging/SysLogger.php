<?php declare(strict_types = 1);

namespace Mys\Logging;

use Exception;
use FilesystemIterator;
use Mys\Core\Logging\Clock;
use Mys\Core\Logging\Logger;
use SplFileObject;

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
     * @var int
     */
    private int $maxLinesPerLog;

    /**
     * @param string $logsFolder
     * @param Clock $clock
     * @param int $maxLinesPerLog
     */
    public function __construct(string $logsFolder, Clock $clock, int $maxLinesPerLog = 10_000) {
        $this->logsFolder = $logsFolder;
        $this->clock = $clock;
        $this->maxLinesPerLog = $maxLinesPerLog;

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
        $fileName = $this->logsFolder . $this->logFileName;
        $file = new SplFileObject($fileName, "r");
        $file->seek(PHP_INT_MAX);
        if ($file->key() >= $this->maxLinesPerLog) {
            $iterator = new FilesystemIterator($this->logsFolder, FilesystemIterator::SKIP_DOTS);
            rename($fileName, $fileName . "." . iterator_count($iterator));
            touch($fileName);
        }

        $message = $this->clock->microtime() . " | $type" . $infoMessage . "\n";
        error_log($message, 3, $fileName);
    }
}