<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\Core\Logging\Logger;
use Mys\Modules\Todo\Persistence\PersistenceReadException;
use Mys\Modules\Todo\Persistence\PersistenceWriteException;
use Mys\Modules\Todo\Persistence\TodoPersistence;
use Throwable;

class TodoController {
    /**
     * @var TodoPersistence
     */
    private TodoPersistence $persistence;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param TodoPersistence $persistence
     * @param Logger $logger
     */
    public function __construct(TodoPersistence $persistence, Logger $logger) {
        $this->persistence = $persistence;
        $this->logger = $logger;
    }

    /**
     * @return TodoEntry[]
     * @throws Exception
     */
    public function getAll(): array {
        try {
            return $this->persistence->getAll();
        }
        catch (Exception $exception) {
            $this->logger->exception($exception);
            throw new PersistenceReadException();
        }
    }

    /**
     * @param CreateTodoRequest $request
     *
     * @return TodoEntry
     * @throws Exception
     */
    public function create(CreateTodoRequest $request): TodoEntry {
        try {
            return $this->persistence->create($request);
        }
        catch (Throwable $exception) {
            $this->logger->exception($exception);
            throw new PersistenceWriteException();
        }
    }

    /**
     * @param int $id
     *
     * @return void
     * @throws Exception
     */
    public function delete(int $id): void {
        $this->persistence->delete($id);
    }

}