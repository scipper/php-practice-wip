<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;

class MockTodoTodoPersistence implements TodoPersistence {
    private mixed $getAllReturnValue;

    /**
     * @return array|null
     * @throws Exception
     */
    public function getAllTodos(): ?array {
        if ($this->getAllReturnValue === "throw") {
            throw new Exception("getAllTodos method throw");
        }
        return $this->getAllReturnValue;
    }

    public function getAllReturns($array): void {
        $this->getAllReturnValue = $array;
    }

}