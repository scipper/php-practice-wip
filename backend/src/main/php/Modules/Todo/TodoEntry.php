<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

readonly class TodoEntry {

    public int $id;

    public string $title;

    public bool $done;

    public function __construct(int $id, string $title, bool $done) {
        $this->id = $id;
        $this->title = $title;
        $this->done = $done;
    }

}