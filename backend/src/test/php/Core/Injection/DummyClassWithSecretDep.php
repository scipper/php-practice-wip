<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

class DummyClassWithSecretDep {

    public function __construct(object $param) {}
}