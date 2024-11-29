<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;

interface TodoPersistence {
    /**
     * @return array|null
     * @throws Exception
     */
    public function getAllTodos(): ?array;
}