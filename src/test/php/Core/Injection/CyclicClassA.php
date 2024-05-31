<?php

namespace Mys\Core\Injection;

class CyclicClassA
{
    public function __construct(CyclicClassB $classB)
    {

    }
}