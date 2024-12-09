<?php declare(strict_types = 1);

namespace Mys\Core\ParameterRecognition;

class DummyParameterComponent
{
    public function voidFunction()
    {
    }

    public function stringFunction(string $param)
    {
    }

    public function twoStringFunction(string $paramA, string $paramB)
    {
    }

    public function intFunction(int $param)
    {
    }

    public function mixedFunction(int $paramA, string $paramB)
    {
    }

    public function classFunction(ClassParameter $param)
    {
    }
}