<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\Modules\Todo\Persistence\PersistenceDeleteException;
use Mys\Modules\Todo\Persistence\PersistenceWriteException;
use Mys\Modules\Todo\Persistence\TodoPersistence;

class MockTodoTodoPersistence implements TodoPersistence {
    private mixed $getAllReturnValue;

    private CreateTodoRequest $createdCalledWith;

    private mixed $createReturnValue;

    private int $deletedCalledWith;

    private string $deleteReturnValue;

    public function getAll(): ?array {
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

    public function delete(int $id): void {
        if ($this->deleteReturnValue === "throw") {
            throw new PersistenceDeleteException();
        }
        $this->deletedCalledWith = $id;
    }

    public function deleteWasCalledWith() {
        return $this->deletedCalledWith;
    }

    public function deleteReturns(string $value) {
        $this->deleteReturnValue = $value;
    }

}