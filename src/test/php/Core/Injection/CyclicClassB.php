<?php

namespace Mys\Core\Injection;

class CyclicClassB
{
    public function __construct(CyclicClassA $classA)
    {

    }
}