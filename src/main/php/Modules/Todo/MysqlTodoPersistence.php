<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use PDO;

class MysqlTodoPersistence implements TodoPersistence {
    /**
     * @var PDO
     */
    private PDO $pdo;

    public function __construct(MysqlConnectionData $connectionData) {
        $host = $connectionData->getHost();
        $port = $connectionData->getPort();
        $db = $connectionData->getDatabase();
        $username = $connectionData->getUsername();
        $password = $connectionData->getPassword();
        $this->pdo = new PDO("mysql:host=$host:$port;dbname=$db", $username, $password);
        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array {
        $todos = [];
        $queryResult = $this->pdo->query("SELECT * FROM todos");
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
        $statement = $this->pdo->prepare("INSERT INTO todos(title) values(:title)");
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
        $statement = $this->pdo->prepare("DELETE FROM todos WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
    }
}