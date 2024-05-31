<?php declare(strict_types=1);

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