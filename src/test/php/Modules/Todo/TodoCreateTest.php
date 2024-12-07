<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\Persistence\PersistenceWriteException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;

class TodoCreateTest extends TestCase {

    private TodoController $controller;

    private MockTodoTodoPersistence $mockPersistence;

    private LoggerSpy $logger;

    /**
     *
     */
    public function setUp(): void {
        $this->mockPersistence = new MockTodoTodoPersistence();
        $this->mockPersistence->createReturns(null);
        $this->logger = new LoggerSpy();
        $this->controller = new TodoController($this->mockPersistence, $this->logger);
        $this->mockPersistence->createReturns(new TodoEntry(0, ""));
    }

    /**
     *
     */
    public function test_module_has_todo_endpoint_for_create_todo() {
        $module = new TodoModule();

        $endpoints = $module->getEndpoints();
        $getAllEndpoint = $this->getEndpoint($endpoints, "/todo", "post");

        $this->assertCount(1, $getAllEndpoint);
        $this->assertEquals("/todo", $getAllEndpoint[0]->getPath());
        $this->assertEquals("post", $getAllEndpoint[0]->getMethod());
    }

    /**
     * @throws Exception
     */
    public function test_creates_a_todo() {
        $request = new CreateTodoRequest(new stdClass());
        $this->controller->create($request);

        $this->assertInstanceOf(CreateTodoRequest::class, $this->mockPersistence->createWasCalledWith());
    }

    /**
     * @throws Exception
     */
    public function test_returns_todo_entry_after_create() {
        $request = new CreateTodoRequest(new stdClass());
        $todo = $this->controller->create($request);

        $this->assertInstanceOf(TodoEntry::class, $todo);
    }

    /**
     * @throws Exception
     */
    public function test_throws_when_creation_fails() {
        $this->expectException(PersistenceWriteException::class);

        $this->mockPersistence->createReturns("throw");

        $request = new CreateTodoRequest(new stdClass());
        $this->controller->create($request);
    }

    /**
     * @throws Exception
     */
    public function test_logs_exception_when_creation_fails() {
        $this->mockPersistence->createReturns("throw");

        $request = new CreateTodoRequest(new stdClass());
        try {
            $this->controller->create($request);
        }
        catch (Throwable $_) {
            $this->assertInstanceOf(PersistenceWriteException::class, $this->logger->exceptionWasCalledWith());
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
