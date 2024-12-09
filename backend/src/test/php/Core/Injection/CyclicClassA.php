<?php declare(strict_types=1);

namespace Mys\Core\Injection;

class CyclicClassA
{
    public function __construct(CyclicClassB $classB)
    {
    }
}