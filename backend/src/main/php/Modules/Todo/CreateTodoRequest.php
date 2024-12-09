<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

readonly class CreateTodoRequest {

    /**
     * @var string
     */
    public string $title;

    /**
     * @param mixed $rawData
     */
    public function __construct(mixed $rawData) {
        $this->title = $rawData->title;
    }

}