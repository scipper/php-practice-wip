<?php declare(strict_types = 1);

namespace Mys\SystemTests\Router;

use Mys\Core\ClassNotFoundException;
use Mys\Core\Injection\ClassAlreadyRegisteredException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
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
    public function tearDown(): void {
        unlink($this->logsFolder . $this->logFile);
        rmdir($this->logsFolder);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws ClassAlreadyRegisteredException
     * @throws CyclicDependencyDetectedException
     */
    public function test_what_ever() {
        $_SERVER["REQUEST_METHOD"] = "GET";

        $response = Router::main($this->logsFolder, $this->moduleListFile);

        $expected = <<<EOJ
{
    "statusCode": 404,
    "statusText": "Not Found",
    "errorMessage": "Http Exception",
    "contentType": "application\/json"
}
EOJ;;
        $this->assertEquals($expected, $response);
    }

    /**
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->logsFolder = __DIR__ . "/logs";
        $this->logFile = "/application.log";
        $this->moduleListFile = __DIR__ . "/module-list.txt";
    }
}
