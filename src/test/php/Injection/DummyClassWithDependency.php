<?php

namespace Mys\Injection;

class DummyClassWithDependency
{
    private DummyDependency $dependency;

    public function __construct(DummyDependency $dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency(): DummyDependency
    {
        return $this->dependency;
    }
}