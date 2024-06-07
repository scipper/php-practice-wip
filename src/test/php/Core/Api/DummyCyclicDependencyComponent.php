<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\Injection\CyclicDependencyDetectedException;

class DummyCyclicDependencyComponent
{
    public function __construct()
    {
        throw new CyclicDependencyDetectedException(["ClassA", "ClassB"]);
    }

    public function path()
    {
    }
}