<?php declare(strict_types = 1);

namespace Mys\Modules\Todo\Persistence;

use Exception;
use Mys\Modules\Todo\CreateTodoRequest;
use Mys\Modules\Todo\TodoEntry;

interface TodoPersistence {
    /**
     * @return TodoEntry[]
     * @throws Exception
     */
    public function getAll(): array;

    /**
     * @param CreateTodoRequest $request
     *
     * @return TodoEntry
     * @throws Exception
     */
    public function create(CreateTodoRequest $request): TodoEntry;

    /**
     * @param int $id
     *
     * @return void
     * @throws Exception
     */
    public function delete(int $id): void;

    /**
     * @param int $id
     *
     * @return void
     * @throws Exception
     */
    public function done(int $id): void;
}