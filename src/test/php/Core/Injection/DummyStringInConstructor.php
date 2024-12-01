<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

class DummyStringInConstructor {
    public function __construct(string $string = "") {}
}