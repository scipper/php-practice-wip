<?php declare(strict_types = 1);

namespace Mys\Modules\Todo\Persistence\Mysql;

use PDO;

class MysqlConnection {
    private PDO $pdo;

    /**
     * @param MysqlConnectionData $connectionData
     */
    public function __construct(MysqlConnectionData $connectionData) {
        $host = $connectionData->getHost();
        $port = $connectionData->getPort();
        $db = $connectionData->getDatabase();
        $username = $connectionData->getUsername();
        $password = $connectionData->getPassword();
        $this->pdo = new PDO("mysql:host=$host:$port", $username, $password);
        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        $this->pdo->query("create database if not exists $db");
        $this->pdo->query("use $db");
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }
}