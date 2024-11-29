<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class TodoModule extends Module {

    public function getServices(): array {
        return [
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

        return [$getAll, $create, $delete];
    }
}