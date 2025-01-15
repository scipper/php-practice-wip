<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\Persistence\PersistenceDeleteException;
use PHPUnit\Framework\TestCase;
use Throwable;

class TodoDeleteTest extends TestCase {

    /**
     * @var TodoController
     */
    private TodoController $controller;

    /**
     * @var MockTodoPersistence
     */
    private MockTodoPersistence $mockPersistence;

    /**
     * @var LoggerSpy
     */
    private LoggerSpy $logger;

    /**
     * @return void
     */
    public function setUp(): void {
        $this->mockPersistence = new MockTodoPersistence();
        $this->mockPersistence->deleteReturns("");
        $this->logger = new LoggerSpy();
        $this->controller = new TodoController($this->mockPersistence, $this->logger);
    }

    /**
     * @return void
     */
    public function test_module_has_todo_endpoint_for_delete_todo() {
        $module = new TodoModule();

        $endpoints = $module->getEndpoints();
        $getAllEndpoint = $this->getEndpoint($endpoints, "/todo", "delete");

        $this->assertCount(1, $getAllEndpoint);
        $this->assertEquals("/todo", $getAllEndpoint[0]->getPath());
        $this->assertEquals("delete", $getAllEndpoint[0]->getMethod());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_deletes_a_todo() {
        $this->controller->delete(1);

        $this->assertEquals(1, $this->mockPersistence->deleteWasCalledWith());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_throws_when_deletion_fails() {
        $this->expectException(PersistenceDeleteException::class);

        $this->mockPersistence->deleteReturns("throw");

        $this->controller->delete(1);
    }

    /**
     * @throws Exception
     */
    public function test_logs_exception_when_delete_fails() {
        $this->mockPersistence->deleteReturns("throw");

        try {
            $this->controller->delete(1);
        }
        catch (Throwable $_) {
            $this->assertInstanceOf(PersistenceDeleteException::class, $this->logger->exceptionWasCalledWith());
        }
    }

    /**
     * @param array $endpoints
     * @param string $path
     * @param string $method
     *
     * @return array
     */
    private function getEndpoint(array $endpoints, string $path, string $method): array {
        return array_values(array_filter($endpoints, function ($endpoint) use ($path, $method) {
            return $endpoint->getPath() === $path &&
                $endpoint->getMethod() === $method;
        }));
    }
}
