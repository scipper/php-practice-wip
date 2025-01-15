<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\Persistence\PersistenceUpdateException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;

class TodoUpdateTest extends TestCase {

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
     * @var UpdateTodoRequest
     */
    private UpdateTodoRequest $request;

    /**
     *
     */
    public function setUp(): void {
        $this->mockPersistence = new MockTodoPersistence();
        $this->mockPersistence->updateReturns("");
        $this->logger = new LoggerSpy();
        $this->controller = new TodoController($this->mockPersistence, $this->logger);
        $this->mockPersistence->updateReturns(new TodoEntry(0, "", true));
        $rawData = new stdClass();
        $rawData->id = 1;
        $rawData->done = true;
        $this->request = new UpdateTodoRequest($rawData);
    }

    /**
     * @return void
     */
    public function test_module_has_todo_endpoint_for_update() {
        $module = new TodoModule();

        $endpoints = $module->getEndpoints();
        $endpoint = $this->getEndpoint($endpoints, "/todo", "patch");

        $this->assertCount(1, $endpoint);
        $this->assertEquals("/todo", $endpoint[0]->getPath());
        $this->assertEquals("patch", $endpoint[0]->getMethod());
    }

    /**
     * @throws Exception
     */
    public function test_marks_todo_as_done() {
        $this->controller->update($this->request);

        $this->assertInstanceOf(UpdateTodoRequest::class, $this->mockPersistence->updateWasCalledWith());
    }

    /**
     * @throws Exception
     */
    public function test_returns_todo_entry_with_done_set_to_true() {
        $todo = $this->controller->update($this->request);

        $this->assertInstanceOf(TodoEntry::class, $todo);
        $this->assertTrue($todo->done);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_throws_when_Update_fails() {
        $this->expectException(PersistenceUpdateException::class);

        $this->mockPersistence->updateReturns("throw");

        $this->controller->update($this->request);
    }

    /**
     * @throws Exception
     */
    public function test_logs_exception_when_update_fails() {
        $this->mockPersistence->updateReturns("throw");

        try {
            $this->controller->update($this->request);
        }
        catch (Throwable $_) {
            $this->assertInstanceOf(PersistenceUpdateException::class, $this->logger->exceptionWasCalledWith());
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
