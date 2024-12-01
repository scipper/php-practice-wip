<?php declare(strict_types = 1);

namespace Mys\Modules\Todo\Persistence\Mysql;

use Mys\Modules\Todo\CreateTodoRequest;
use Mys\Modules\Todo\Persistence\TodoPersistence;
use Mys\Modules\Todo\TodoEntry;
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
     * @return array|null
     */
    public function getAll(): ?array {
        $todos = [];
        $queryResult = $this->pdo->query("SELECT * FROM " . self::$TABLE_NAME);
        foreach ($queryResult as $row) {
            $todos[] = new TodoEntry($row["id"], $row["title"]);
        }
        return $todos;
    }

    /**
     * @param CreateTodoRequest $request
     *
     * @return TodoEntry|null
     */
    public function create(CreateTodoRequest $request): ?TodoEntry {
        $title = $request->getTitle();
        $statement = $this->pdo->prepare("INSERT INTO " . self::$TABLE_NAME . "(title) values(:title)");
        $statement->bindParam(":title", $title);
        $statement->execute();
        $id = $this->pdo->lastInsertId();

        return new TodoEntry((int)$id, $title);
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
}