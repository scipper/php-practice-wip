<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;
use Mys\Core\Logging\Logger;

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
            $todos = $this->persistence->getAll();
            if (is_array($todos)) {
                return $todos;
            }
            throw new NullReturnsFromPersistenceException();
        }
        catch (Exception $exception) {
            $this->logger->exception($exception);
            return [];
        }
    }

    /**
     * @param CreateTodoRequest $request
     *
     * @return TodoEntry|null
     * @throws Exception
     */
    public function create(CreateTodoRequest $request): TodoEntry|null {
        return $this->persistence->create($request);
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