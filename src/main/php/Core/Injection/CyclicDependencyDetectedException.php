<?php

namespace Mys\Core\Injection;

use Exception;

class CyclicDependencyDetectedException extends Exception
{
    public function __construct(array $callChain)
    {
        $join = join(" -> ", $callChain);
        parent::__construct("CyclicDependencyDetectedException: $join");
    }
}