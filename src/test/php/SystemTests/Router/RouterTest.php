<?php declare(strict_types = 1);

namespace Mys\SystemTests\Router;

use Mys\Core\Injection\ClassAlreadyRegisteredException;
use Mys\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
    /**
     * @var string
     */
    private string $logsFolder;

    /**
     * @var string
     */
    private string $moduleListFile;

    /**
     * @var string
     */
    private string $logFile;

    /**
     * @return void
     */
    public function setUp(): void {
        parent::setUp();
        $this->logsFolder = __DIR__ . "/logs";
        $this->logFile = "/application.log";
        $this->moduleListFile = __DIR__ . "/module-list.txt";
    }

    /**
     * @return void
     */
    public function tearDown(): void {
        unlink($this->logsFolder . $this->logFile);
        rmdir($this->logsFolder);
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function test_404_not_found() {
        $_SERVER["REQUEST_METHOD"] = "GET";

        $response = Router::main($this->logsFolder, $this->moduleListFile);

        $this->assertEquals(JsonResponses::get404(), $response);
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function test_return_internal_server_error_on_exception_in_controller() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_SERVER["REDIRECT_URL"] = "/internalServerError";

        $response = Router::main($this->logsFolder, $this->moduleListFile);

        $this->assertEquals(JsonResponses::get500("expected exception"), $response);
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function test_return_internal_server_error_when_controller_class_is_not_found() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_SERVER["REDIRECT_URL"] = "/classNotFound";

        $response = Router::main($this->logsFolder, $this->moduleListFile);

        $this->assertEquals(JsonResponses::get500("ClassNotFoundException"), $response);
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function test_return_internal_server_error_on_fatal_error() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_SERVER["REDIRECT_URL"] = "/fatal";

        $response = Router::main($this->logsFolder, $this->moduleListFile);

        $this->assertEquals(JsonResponses::get500("Call to undefined function ()"), $response);
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function test_logs_trace_on_fatal_error() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_SERVER["REDIRECT_URL"] = "/fatal";

        Router::main($this->logsFolder, $this->moduleListFile);

        $logs = file_get_contents($this->logsFolder . $this->logFile);
        $logsArray = explode("\n", $logs);
        $this->assertMatchesRegularExpression("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{6} \| ERROR: Call to undefined function \(\)/", $logsArray[0]);
        $this->assertStringContainsString("| #0", $logsArray[1]);
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function test_return_logs_when_module_list_is_not_found() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $this->moduleListFile = "no-module-list.txt";

        Router::main($this->logsFolder, $this->moduleListFile);

        $logs = file_get_contents($this->logsFolder . $this->logFile);
        $logsArray = explode("\n", $logs);
        $this->assertMatchesRegularExpression(
            "/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{6} \| WARNING: No module list found under path 'no-module-list.txt'/", $logsArray[0]
        );
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     */
    public function test_return_success_response() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_SERVER["REDIRECT_URL"] = "/success";

        $response = Router::main($this->logsFolder, $this->moduleListFile);

        $this->assertEquals(JsonResponses::get200(), $response);
    }
}
