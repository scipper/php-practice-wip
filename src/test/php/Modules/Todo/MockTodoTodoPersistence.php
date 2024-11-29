<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;

class MockTodoTodoPersistence implements TodoPersistence {
    private mixed $getAllReturnValue;

    private CreateTodoRequest $createdCalledWith;

    private mixed $createReturnValue;

    public function getAllTodos(): ?array {
        if ($this->getAllReturnValue === "throw") {
            throw new Exception("getAllTodos method throw");
        }
        return $this->getAllReturnValue;
    }

    public function getAllReturns($array): void {
        $this->getAllReturnValue = $array;
    }

    public function create(CreateTodoRequest $request): ?TodoEntry {
        if ($this->createReturnValue === "throw") {
            throw new PersistenceWriteException();
        }
        $this->createdCalledWith = $request;
        return $this->createReturnValue;
    }

    public function createWasCalledWith(): CreateTodoRequest {
        return $this->createdCalledWith;
    }

    public function createReturns($todo) {
        $this->createReturnValue = $todo;
    }

}