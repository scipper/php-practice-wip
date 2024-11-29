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
     * @return string[]
     * @throws Exception
     */
    public function getAll(): array {
        try {
            $todos = $this->persistence->getAllTodos();
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
     * @param TodoPersistence $persistence
     * @param Logger $logger
     */
    public function __construct(TodoPersistence $persistence, Logger $logger) {
        $this->persistence = $persistence;
        $this->logger = $logger;
    }
}