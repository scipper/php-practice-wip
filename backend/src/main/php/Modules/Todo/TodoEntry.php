<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

readonly class TodoEntry {

    public int $id;

    public string $title;

    public function __construct(int $id, string $title) {
        $this->id = $id;
        $this->title = $title;
    }

}