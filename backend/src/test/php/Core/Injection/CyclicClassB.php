<?php declare(strict_types=1);

namespace Mys\Core\Injection;

class CyclicClassB
{
    public function __construct(CyclicClassA $classA)
    {
    }
}