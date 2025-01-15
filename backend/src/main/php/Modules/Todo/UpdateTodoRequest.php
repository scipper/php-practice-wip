<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

readonly class UpdateTodoRequest {

    /**
     * @var int
     */
    public int $id;

    /**
     * @var bool
     */
    public bool $done;

    /**
     * @param mixed $rawData
     */
    public function __construct(mixed $rawData) {
        $this->id = $rawData->id;
        $this->done = (bool)$rawData->done;
    }

}