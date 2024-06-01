<?php declare(strict_types = 1);

namespace Mys\Core\ParameterRecognition;

use ReflectionClass;
use ValueError;

class ParameterRecognition
{
    public function __construct()
    {
    }

    public function recognise(string $class, string $function, ...$payload): array
    {
        $parameters = [];
        $reflectionClass = new ReflectionClass($class);
        $reflectionMethod = $reflectionClass->getMethod($function);
        $reflectionParameters = $reflectionMethod->getParameters();
        foreach ($reflectionParameters as $index => $reflectionParameter)
        {
            $reflectionIntersectionType = $reflectionParameter->getType();
            $type = $reflectionIntersectionType->getName();
            try
            {
                settype($payload[$index], $type);
                $parameters[] = $payload[$index];
            }
            catch (ValueError $error)
            {
                $json = json_decode($payload[$index]);
                $classParam = new $type($json);
                $parameters[] = $classParam;
            }
        }
        return $parameters;
    }
}