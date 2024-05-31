<?php

namespace Mys\Modules\Test;

class TestComponent
{
    private TestService $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function greeting(): void
    {
        echo $this->testService->sayHello();
    }
}