<?php declare(strict_types = 1);

namespace Mys\Core\Api;

class DummyApi
{
    public static bool $pathGetWasCalled = false;

    public static bool $pathPostWasCalled = false;

    public static bool $pathParamWasCalledCorrectly = false;

    public function pathGet()
    {
        self::$pathGetWasCalled = true;
    }

    public function pathPost()
    {
        self::$pathPostWasCalled = true;
    }

    public function pathParam(int $param)
    {
        self::$pathParamWasCalledCorrectly = is_int($param);
    }
}