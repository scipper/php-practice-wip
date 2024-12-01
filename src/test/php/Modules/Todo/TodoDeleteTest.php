<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\Persistence\PersistenceDeleteException;
use PHPUnit\Framework\TestCase;

class TodoDeleteTest extends TestCase {

    /**
     * @var TodoController
     */
    private TodoController $controller;

    /**
     * @var MockTodoTodoPersistence
     */
    private MockTodoTodoPersistence $mockPersistence;

    /**
     * @return void
     */
    public function setUp(): void {
        $this->mockPersistence = new MockTodoTodoPersistence();
        $this->mockPersistence->deleteReturns("");
        $this->controller = new TodoController($this->mockPersistence, new LoggerSpy());
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
