<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

class DummyIntInConstructor {
    public function __construct(int $int = 0) {}
}