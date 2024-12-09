<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Exception;
use Mys\Core\Api\HttpStatus\ServerExceptions\InternalServerErrorException;
use TypeError;

class DummyApi
{
    public static bool $pathGetWasCalled = false;

    public static bool $pathPostWasCalled = false;

    public static bool $pathParamWasCalledCorrectly = false;

    public function pathGet()
    {
        self::$pathGetWasCalled = true;

        return "content";
    }

    public function pathPost()
    {
        self::$pathPostWasCalled = true;
    }

    public function pathParam(int $param)
    {
        self::$pathParamWasCalledCorrectly = is_int($param);
    }

    public function throwsException()
    {
        throw new InternalServerErrorException("some internal server error");
    }

    public function unhandledException()
    {
        throw new Exception("Something went wrong");
    }

    public function typeError()
    {
        throw new TypeError("Something typed wrong");
    }

    public function getNumber()
    {
        return 1;
    }

    public function getDouble()
    {
        return 1.1;
    }

    public function getBool()
    {
        return false;
    }

    public function getArray()
    {
        return ["value"];
    }

    public function getObject()
    {
        return new JsonObject("FirstTest", "LastTest");
    }
}