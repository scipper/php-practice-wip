<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

class CreateTodoRequest {

    /**
     * @var string|null
     */
    public ?string $title;

    /**
     * @param mixed $rawData
     */
    public function __construct(mixed $rawData) {
        if (property_exists($rawData, "title")) {
            $this->title = $rawData->title;
        }
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }
}