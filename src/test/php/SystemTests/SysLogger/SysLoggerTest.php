<?php declare(strict_types = 1);

namespace Mys\SystemTests\SysLogger;

use Exception;
use Mys\Core\Logging\Clock;
use Mys\Logging\DateTimeClock;
use Mys\Logging\SysLogger;
use PHPUnit\Framework\TestCase;

class SysLoggerTest extends TestCase {

    /**
     * @var string
     */
    private string $logsFolder;

    /**
     * @var string
     */
    private string $logFile;

    /**
     * @var Clock
     */
    private Clock $clock;

    /**
     * @var SysLogger
     */
    private SysLogger $sysLogger;

    /**
     * @var string
     */
    private string $dateFormat;

    /**
     * @return void
     */
    public function setUp(): void {
        $this->logsFolder = __DIR__ . "/logs";
        $this->logFile = "/application.log";
        $this->clock = new DateTimeClock();
        $this->sysLogger = new SysLogger($this->logsFolder, $this->clock);
        $this->dateFormat = "\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{6}";
    }

    /**
     * @return void
     */
    public function tearDown(): void {
        unlink($this->logsFolder . $this->logFile);
        if (is_file($this->logsFolder . $this->logFile . ".1")) {
            unlink($this->logsFolder . $this->logFile . ".1");
        }
        if (is_file($this->logsFolder . $this->logFile . ".2")) {
            unlink($this->logsFolder . $this->logFile . ".2");
        }
        rmdir($this->logsFolder);
    }

    /**
     * @return void
     */
    public function test_creates_log_folder() {
        $this->assertTrue(is_dir($this->logsFolder));
    }

    /**
     * @return void
     */
    public function test_does_not_throw_when_logs_folder_already_exists() {
        new SysLogger($this->logsFolder, $this->clock);

        $this->assertTrue(is_dir($this->logsFolder));
    }

    /**
     * @return void
     */
    public function test_creates_log_file() {
        $this->assertTrue(is_file($this->logsFolder . $this->logFile));
    }

    /**
     * @return void
     */
    public function test_info_log() {
        $this->sysLogger->info("info message");
        $logs = file_get_contents($this->logsFolder . $this->logFile);
        $this->assertMatchesRegularExpression("/^$this->dateFormat \| INFO: info message\\n/", $logs);
    }

    /**
     * @return void
     */
    public function test_warning_log() {
        $this->sysLogger->warning("warning message");
        $logs = file_get_contents($this->logsFolder . $this->logFile);
        $this->assertMatchesRegularExpression("/^$this->dateFormat \| WARNING: warning message\\n/", $logs);
    }

    /**
     * @return void
     */
    public function test_error_log() {
        $this->sysLogger->error("error message");
        $logs = file_get_contents($this->logsFolder . $this->logFile);
        $this->assertMatchesRegularExpression("/^$this->dateFormat \| ERROR: error message\\n/", $logs);
    }

    /**
     * @return void
     */
    public function test_adds_new_line_after_each_log() {
        $this->sysLogger->info("info message 1");
        $this->sysLogger->info("info message 2");
        $logs = file_get_contents($this->logsFolder . $this->logFile);
        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 1\\n$this->dateFormat \| INFO: info message 2\\n/",
            $logs
        );
    }

    /**
     * @return void
     */
    public function test_exception_log() {
        $this->sysLogger->exception(new Exception("exception message"));
        $logs = file_get_contents($this->logsFolder . $this->logFile);
        $logsArray = explode("\n", $logs);
        $this->assertMatchesRegularExpression("/^$this->dateFormat \| Exception: exception message/", $logsArray[0]);
        $this->assertStringContainsString("| #0", $logsArray[1]);
        $this->assertStringContainsString("| #1", $logsArray[2]);
    }

    /**
     * @return void
     */
    public function test_rotates_logfile_after_a_certain_amount_of_lines() {
        $this->sysLogger = new SysLogger($this->logsFolder, $this->clock, 2);
        $this->sysLogger->info("info message 1");
        $this->sysLogger->info("info message 2");
        $this->sysLogger->info("info message 3");
        $this->sysLogger->info("info message 4");

        $currentLogs = file_get_contents($this->logsFolder . $this->logFile);
        $rotatedLogs = file_get_contents($this->logsFolder . $this->logFile . ".1");

        $currentLogsArray = explode("\n", $currentLogs);
        $rotatedLogsArray = explode("\n", $rotatedLogs);

        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 3/",
            $currentLogsArray[0]
        );
        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 4/",
            $currentLogsArray[1]
        );
        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 1/",
            $rotatedLogsArray[0]
        );
        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 2/",
            $rotatedLogsArray[1]
        );
    }

    /**
     * @return void
     */
    public function test_rotates_multiple_times() {
        $this->sysLogger = new SysLogger($this->logsFolder, $this->clock, 1);
        $this->sysLogger->info("info message 1");
        $this->sysLogger->info("info message 2");
        $this->sysLogger->info("info message 3");

        $currentLogs = file_get_contents($this->logsFolder . $this->logFile);
        $rotatedLogs1 = file_get_contents($this->logsFolder . $this->logFile . ".1");
        $rotatedLogs2 = file_get_contents($this->logsFolder . $this->logFile . ".2");

        $currentLogsArray = explode("\n", $currentLogs);
        $rotatedLogsArray1 = explode("\n", $rotatedLogs1);
        $rotatedLogsArray2 = explode("\n", $rotatedLogs2);

        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 3/",
            $currentLogsArray[0]
        );
        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 1/",
            $rotatedLogsArray1[0]
        );
        $this->assertMatchesRegularExpression(
            "/^$this->dateFormat \| INFO: info message 2/",
            $rotatedLogsArray2[0]
        );
    }
}
