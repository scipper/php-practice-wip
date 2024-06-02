<?php declare(strict_types=1);

namespace Mys\Core;

class DummyClassWithDependency
{
    public static bool $getDependencyWasCalled = false;

    private DummyDependency $dependency;

    public function __construct(DummyDependency $dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency(): DummyDependency
    {
        self::$getDependencyWasCalled = true;
        return $this->dependency;
    }
}