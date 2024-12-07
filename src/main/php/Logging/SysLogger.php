<?php declare(strict_types = 1);

namespace Mys\Logging;

use FilesystemIterator;
use Mys\Core\Logging\Clock;
use Mys\Core\Logging\Logger;
use SplFileObject;
use Throwable;

use function error_log;

class SysLogger implements Logger {

    /**
     * @var string
     */
    private string $logsFolder;

    /**
     * @var Clock
     */
    private Clock $clock;

    /**
     * @var int
     */
    private int $maxLinesPerLog;

    /**
     * @var string
     */
    private string $logFile;

    /**
     * @param string $logsFolder
     * @param Clock $clock
     * @param int $maxLinesPerLog
     */
    public function __construct(string $logsFolder, Clock $clock, int $maxLinesPerLog = 10_000) {
        $this->logsFolder = $logsFolder;
        $this->clock = $clock;
        $this->maxLinesPerLog = $maxLinesPerLog;
        $this->logFile = $this->logsFolder . "/application.log";

        if (!is_dir($this->logsFolder)) {
            mkdir($this->logsFolder, 0777, true);
        }
        touch($this->logFile);
    }

    /**
     * @param Throwable $errorClass
     *
     * @return void
     */
    public function exception(Throwable $errorClass): void {
        $this->log($errorClass::class . ": ", $errorClass->getMessage());
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
        $numberOfCurrentLogFiles = $this->getNumberOfCurrentLogFiles($this->logFile);
        if ($this->newLogFileNeeded($numberOfCurrentLogFiles)) {
            $this->rotateLogFile($this->logFile);
        }

        $message = $this->clock->microtime() . " | $type" . $infoMessage . "\n";
        error_log($message, 3, $this->logFile);
    }

    /**
     * @param string $fileName
     *
     * @return int
     */
    private function getNumberOfCurrentLogFiles(string $fileName): int {
        $file = new SplFileObject($fileName, "r");
        $file->seek(PHP_INT_MAX);
        return $file->key();
    }

    /**
     * @param int $numberOfCurrentLogFiles
     *
     * @return bool
     */
    private function newLogFileNeeded(int $numberOfCurrentLogFiles): bool {
        return $numberOfCurrentLogFiles >= $this->maxLinesPerLog;
    }

    /**
     * @param string $fileName
     *
     * @return void
     */
    private function rotateLogFile(string $fileName): void {
        $iterator = new FilesystemIterator($this->logsFolder, FilesystemIterator::SKIP_DOTS);
        rename($fileName, $fileName . "." . iterator_count($iterator));
        touch($fileName);
    }
}