<?php declare(strict_types = 1);

namespace Mys\Modules\Todo\Persistence\Mysql;

use Exception;
use Mys\Modules\Todo\CreateTodoRequest;
use Mys\Modules\Todo\Persistence\TodoPersistence;
use Mys\Modules\Todo\TodoEntry;
use Mys\Modules\Todo\UpdateTodoRequest;
use PDO;

class MysqlTodoPersistence implements TodoPersistence {
    /**
     * @var PDO
     */
    private PDO $pdo;

    public static string $TABLE_NAME = "todos";

    public function __construct(MysqlConnection $connection) {
        $this->pdo = $connection->getConnection();
        $sql = file_get_contents(__DIR__ . "/todos.sql");
        $this->pdo->exec($sql);
    }

    /**
     * @return TodoEntry[]
     */
    public function getAll(): array {
        $todos = [];
        $queryResult = $this->pdo->query("SELECT * FROM " . self::$TABLE_NAME);
        foreach ($queryResult as $row) {
            $todos[] = new TodoEntry($row["id"], $row["title"], (bool)$row["done"]);
        }
        return $todos;
    }

    /**
     * @param CreateTodoRequest $request
     *
     * @return TodoEntry
     */
    public function create(CreateTodoRequest $request): TodoEntry {
        $title = $request->title;
        $statement = $this->pdo->prepare("INSERT INTO " . self::$TABLE_NAME . "(title) values(:title)");
        $statement->bindParam(":title", $title);
        $statement->execute();
        $id = $this->pdo->lastInsertId();

        return new TodoEntry((int)$id, $title, false);
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void {
        $statement = $this->pdo->prepare("DELETE FROM " . self::$TABLE_NAME . " WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    /**
     * @param UpdateTodoRequest $request
     *
     * @return TodoEntry
     * @throws Exception
     */
    public function update(UpdateTodoRequest $request): TodoEntry {
        $statement = $this->pdo->prepare("UPDATE " . self::$TABLE_NAME . " SET done = :done WHERE id = :id");
        $id = $request->id;
        $done = (int)$request->done;
        $statement->bindParam(":id", $id);
        $statement->bindParam(":done", $done);
        $statement->execute();

        $statement = $this->pdo->prepare("SELECT * FROM " . self::$TABLE_NAME . " WHERE id = :id LIMIT 1");
        $statement->bindParam(":id", $id);
        $statement->execute();
        $todo = $statement->fetch(PDO::FETCH_ASSOC);

        return new TodoEntry($todo["id"], $todo["title"], (bool)$todo["done"]);
    }
}