<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\Persistence\PersistenceReadException;
use PHPUnit\Framework\TestCase;
use Throwable;

class TodoGetAllTest extends TestCase {

    /**
     * @var TodoController
     */
    private TodoController $controller;

    /**
     * @var MockTodoTodoPersistence
     */
    private MockTodoTodoPersistence $mockPersistence;

    /**
     * @var LoggerSpy
     */
    private LoggerSpy $loggerSpy;

    /**
     * @return void
     */
    public function setUp(): void {
        $this->loggerSpy = new LoggerSpy();
        $this->mockPersistence = new MockTodoTodoPersistence();
        $this->mockPersistence->getAllReturns([]);
        $this->controller = new TodoController($this->mockPersistence, $this->loggerSpy);
    }

    /**
     * @return void
     */
    public function test_module_has_todo_endpoint_for_get_all_todos() {
        $module = new TodoModule();

        $endpoints = $module->getEndpoints();
        $getAllEndpoint = $this->getEndpoint($endpoints, "/todo", "get");

        $this->assertCount(1, $getAllEndpoint);
        $this->assertEquals("/todo", $getAllEndpoint[0]->getPath());
        $this->assertEquals("get", $getAllEndpoint[0]->getMethod());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_returns_an_empty_array_when_no_todos_are_created() {
        $result = $this->controller->getAll();

        $this->assertSame($result, []);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_returns_todo() {
        $todoEntry = new TodoEntry(0, "");
        $this->mockPersistence->getAllReturns([$todoEntry]);

        $result = $this->controller->getAll();

        $this->assertSame($result, [$todoEntry]);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_throws_when_get_all_fails() {
        $this->expectException(PersistenceReadException::class);

        $this->mockPersistence->getAllReturns("throw");

        $result = $this->controller->getAll();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_logs_exception_when_persistence_throws() {
        $this->mockPersistence->getAllReturns("throw");

        try {
            $this->controller->getAll();
        }
        catch (Throwable $_) {
            $this->assertInstanceOf(PersistenceReadException::class, $this->loggerSpy->exceptionWasCalledWith());
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
