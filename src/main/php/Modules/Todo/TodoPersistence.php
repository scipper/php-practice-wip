<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;

interface TodoPersistence {
    /**
     * @return TodoEntry|null
     * @throws Exception
     */
    public function getAll(): ?array;

    /**
     * @param CreateTodoRequest $request
     *
     * @return TodoEntry|null
     * @throws Exception
     */
    public function create(CreateTodoRequest $request): ?TodoEntry;

    /**
     * @param int $id
     *
     * @return void
     * @throws Exception
     */
    public function delete(int $id): void;
}