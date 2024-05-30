<?php

namespace Mys\test;

class TestComponent
{
    private TestService $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function greeting()
    {
        echo $this->testService->sayHello();
    }
}