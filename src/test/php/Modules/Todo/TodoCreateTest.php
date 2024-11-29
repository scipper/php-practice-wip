<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use PHPUnit\Framework\TestCase;

class TodoCreateTest extends TestCase {

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
        $this->mockPersistence->createReturns(null);
        $this->controller = new TodoController($this->mockPersistence, $this->loggerSpy);
    }

    /**
     * @return void
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
        $request = new CreateTodoRequest();
        $this->controller->create($request);

        $this->assertInstanceOf(CreateTodoRequest::class, $this->mockPersistence->createWasCalledWith());
    }

    /**
     * @throws Exception
     */
    public function test_returns_todo_entry_after_create() {
        $this->mockPersistence->createReturns(new TodoEntry());

        $request = new CreateTodoRequest();
        $todo = $this->controller->create($request);

        $this->assertInstanceOf(TodoEntry::class, $todo);
    }

    /**
     * @throws Exception
     */
    public function test_throws_when_creation_fails() {
        $this->expectException(PersistenceWriteException::class);

        $this->mockPersistence->createReturns("throw");

        $request = new CreateTodoRequest();
        $this->controller->create($request);
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
