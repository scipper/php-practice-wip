<?php declare(strict_types=1);

namespace Mys\Modules\Test;

class TestService
{
    public function __construct(TestBService $service)
    {
    }

    public function sayHello(): string
    {
        return "hello";
    }
}