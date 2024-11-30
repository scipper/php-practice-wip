<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

class MysqlConnectionData {
    /**
     * @var string
     */
    private string $host;

    /**
     * @var int
     */
    private int $port;

    /**
     * @var string
     */
    private string $database;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $password;

    public function __construct() {
        $file = parse_ini_file(__DIR__ . "/../../../../../config/mysql/connection-data.ini");
        $this->host = $file["host"];
        $this->port = (int)$file["port"];
        $this->database = $file["database"];
        $this->username = $file["username"];
        $this->password = $file["password"];
    }

    /**
     * @return string
     */
    public function getHost(): string {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getDatabase(): string {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }
}