<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class TodoModule extends Module {

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array {
        $getAll = new Endpoint(TodoController::class, "getAll");
        $getAll->setPath("/todo");

        return [$getAll];
    }
}