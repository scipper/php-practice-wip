<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\Persistence\PersistenceUpdateException;
use PHPUnit\Framework\TestCase;
use Throwable;

class TodoMarkAsDoneTest extends TestCase {

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
    private LoggerSpy $logger;

    /**
     *
     */
    public function setUp(): void {
        $this->mockPersistence = new MockTodoTodoPersistence();
        $this->mockPersistence->doneReturns("");
        $this->logger = new LoggerSpy();
        $this->controller = new TodoController($this->mockPersistence, $this->logger);
        $this->mockPersistence->createReturns(new TodoEntry(0, ""));
    }

    /**
     * @return void
     */
    public function test_module_has_todo_endpoint_for_mark_as_done() {
        $module = new TodoModule();

        $endpoints = $module->getEndpoints();
        $endpoint = $this->getEndpoint($endpoints, "/todo/done", "post");

        $this->assertCount(1, $endpoint);
        $this->assertEquals("/todo/done", $endpoint[0]->getPath());
        $this->assertEquals("post", $endpoint[0]->getMethod());
    }

    /**
     * @throws Exception
     */
    public function test_marks_todo_as_done() {
        $this->controller->done(1);

        $this->assertEquals(1, $this->mockPersistence->doneWasCalledWith());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_throws_when_done_fails() {
        $this->expectException(PersistenceUpdateException::class);

        $this->mockPersistence->doneReturns("throw");

        $this->controller->done(1);
    }

    /**
     * @throws Exception
     */
    public function test_logs_exception_when_done_fails() {
        $this->mockPersistence->doneReturns("throw");

        try {
            $this->controller->done(1);
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
