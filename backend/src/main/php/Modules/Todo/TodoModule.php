<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;
use Mys\Modules\Todo\Persistence\Mysql\MysqlConnectionData;
use Mys\Modules\Todo\Persistence\Mysql\MysqlTodoPersistence;
use Mys\Modules\Todo\Persistence\TodoPersistence;

class TodoModule extends Module {

    public function getServices(): array {
        $configFile = __DIR__ . "/../../../../../config/mysql/connection-data.ini";
        return [
            MysqlConnectionData::class => new MysqlConnectionData($configFile),
            TodoPersistence::class => MysqlTodoPersistence::class,
        ];
    }

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array {
        $getAll = new Endpoint(TodoController::class, "getAll");
        $getAll->setPath("/todo");

        $create = new Endpoint(TodoController::class, "create");
        $create->setPath("/todo");
        $create->setMethod("post");

        $delete = new Endpoint(TodoController::class, "delete");
        $delete->setPath("/todo");
        $delete->setMethod("delete");

        $markAsDone = new Endpoint(TodoController::class, "done");
        $markAsDone->setPath("/todo/done");
        $markAsDone->setMethod("post");

        return [$getAll, $create, $delete, $markAsDone];
    }
}