<?php declare(strict_types = 1);

namespace Mys\SystemTests\Modules\Todo;

use Exception;
use Mys\LoggerSpy;
use Mys\Modules\Todo\CreateTodoRequest;
use Mys\Modules\Todo\Persistence\Mysql\MysqlConnection;
use Mys\Modules\Todo\Persistence\Mysql\MysqlConnectionData;
use Mys\Modules\Todo\Persistence\Mysql\MysqlTodoPersistence;
use Mys\Modules\Todo\TodoController;
use Mys\Modules\Todo\UpdateTodoRequest;
use PDO;
use PHPUnit\Framework\TestCase;
use stdClass;

class MysqlTodoPersistenceTest extends TestCase {

    private MysqlConnectionData $connectionData;

    private PDO $pdo;

    private MysqlTodoPersistence $persistence;

    /**
     * @return void
     */
    public function setUp(): void {
        $this->connectionData = new MysqlConnectionData(__DIR__ . "/../../../../../../config/mysql/connection-data.ini");
        $connection = new MysqlConnection($this->connectionData);
        $this->persistence = new MysqlTodoPersistence($connection);
        $this->pdo = $connection->getConnection();
    }

    /**
     * @return void
     */
    public function tearDown(): void {
        $this->pdo->query("drop database if exists " . $this->connectionData->getDatabase());
    }

    /**
     * @return void
     */
    public function test_establishes_connection_to_mysql_server() {
        $this->assertInstanceOf(PDO::class, $this->pdo);
    }

    /**
     * @return void
     */
    public function test_creates_database() {
        $statement = $this->pdo->query("show databases");
        $databases = [];
        foreach ($statement as $row) {
            $databases[] = $row[0];
        }

        $this->assertContains($this->connectionData->getDatabase(), $databases);
    }

    public function test_creates_todos_table() {
        $statement = $this->pdo->query("show tables");

        $tables = [];
        foreach ($statement as $row) {
            $tables[] = $row[0];
        }

        $this->assertContains(MysqlTodoPersistence::$TABLE_NAME, $tables);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_created_one_todo() {
        $controller = new TodoController($this->persistence, new LoggerSpy());
        $requestRaw = new stdClass();
        $requestRaw->title = "Test Todo";
        $request = new CreateTodoRequest($requestRaw);

        $controller->create($request);
        $todos = $controller->getAll();

        $this->assertCount(1, $todos);
        $this->assertGreaterThan(0, $todos[0]->id);
        $this->assertEquals("Test Todo", $todos[0]->title);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_deleted_one_todo() {
        $controller = new TodoController($this->persistence, new LoggerSpy());
        $requestRaw = new stdClass();
        $requestRaw->title = "Test Todo";
        $request = new CreateTodoRequest($requestRaw);

        $todoEntry = $controller->create($request);
        $controller->delete($todoEntry->id);
        $todos = $controller->getAll();

        $this->assertCount(0, $todos);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_marks_todo_as_done() {
        $controller = new TodoController($this->persistence, new LoggerSpy());
        $createRequestRaw = new stdClass();
        $createRequestRaw->title = "Test Todo";
        $createRequest = new CreateTodoRequest($createRequestRaw);
        $todoEntry = $controller->create($createRequest);

        $updateRequestRaw = new stdClass();
        $updateRequestRaw->id = $todoEntry->id;
        $updateRequestRaw->done = true;
        $updateRequest = new UpdateTodoRequest($updateRequestRaw);

        $todoDone = $controller->update($updateRequest);

        $this->assertTrue($todoDone->done);
    }
}
