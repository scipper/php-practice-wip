<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Mys\Modules\Todo\Persistence\PersistenceDeleteException;
use Mys\Modules\Todo\Persistence\PersistenceReadException;
use Mys\Modules\Todo\Persistence\PersistenceUpdateException;
use Mys\Modules\Todo\Persistence\PersistenceWriteException;
use Mys\Modules\Todo\Persistence\TodoPersistence;

class MockTodoTodoPersistence implements TodoPersistence {
    private mixed $getAllReturnValue;

    private CreateTodoRequest $createdCalledWith;

    private mixed $createReturnValue;

    private int $deletedCalledWith;

    private string $deleteReturnValue;

    private int $doneCalledWith;

    private string $doneReturnValue;

    public function getAll(): array {
        if ($this->getAllReturnValue === "throw") {
            throw new PersistenceReadException();
        }
        return $this->getAllReturnValue;
    }

    public function getAllReturns($array): void {
        $this->getAllReturnValue = $array;
    }

    public function create(CreateTodoRequest $request): TodoEntry {
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

    public function doneWasCalledWith() {
        return $this->doneCalledWith;
    }

    public function done(int $id): void {
        if ($this->doneReturnValue === "throw") {
            throw new PersistenceUpdateException();
        }
        $this->doneCalledWith = $id;
    }

    public function doneReturns(string $value) {
        $this->doneReturnValue = $value;
    }

}