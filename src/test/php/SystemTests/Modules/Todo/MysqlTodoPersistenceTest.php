<?php declare(strict_types = 1);

namespace Mys\SystemTests\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\CreateTodoRequest;
use Mys\Modules\Todo\MysqlTodoPersistence;
use Mys\Modules\Todo\TodoController;
use PHPUnit\Framework\TestCase;
use stdClass;

class MysqlTodoPersistenceTest extends TestCase {
    /**
     * @throws Exception
     */
    public function test_created_one_todo() {
        $todoPersistence = new MysqlTodoPersistence();
        $controller = new TodoController($todoPersistence, new LoggerSpy());
        $requestRaw = new stdClass();
        $requestRaw->title = "Test Todo";
        $request = new CreateTodoRequest($requestRaw);
        $controller->create($request);

        $todos = $controller->getAll();

        $this->assertCount(1, $todos);
        $this->assertGreaterThan(0, $todos[0]->getId());
        $this->assertEquals("Test Todo", $todos[0]->getTitle());

        $controller->delete($todos[0]->getId());
    }
}
