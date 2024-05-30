<?php

namespace Mys\test;

class TestModule implements Module
{
    public function getClasses(): array
    {
        return [
            TestComponent::class
        ];
    }
}