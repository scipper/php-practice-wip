<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

class DummyClassWithDeps {
    public function __construct(DummyClass $dummyClass) {}
}