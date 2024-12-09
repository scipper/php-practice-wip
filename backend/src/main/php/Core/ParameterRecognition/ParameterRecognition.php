<?php declare(strict_types = 1);

namespace Mys\Core\ParameterRecognition;

use Mys\Core\ClassNotFoundException;
use ReflectionClass;
use ReflectionException;
use ValueError;

class ParameterRecognition
{
    /**
     * @param string $injectionToken
     * @param string $function
     * @param string[] $payload
     *
     * @return array
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function recognise(string $injectionToken, string $function, ...$payload): array
    {
        $parameters = [];

        try
        {
            $reflectionClass = new ReflectionClass($injectionToken);
            if (!method_exists($injectionToken, $function))
            {
                throw new FunctionNotFoundException();
            }
            $reflectionMethod = $reflectionClass->getMethod($function);
            $reflectionParameters = $reflectionMethod->getParameters();
            foreach ($reflectionParameters as $index => $reflectionParameter)
            {
                $reflectionIntersectionType = $reflectionParameter->getType();
                $type = $reflectionIntersectionType->getName();
                try
                {
                    if (!array_key_exists($index, $payload))
                    {
                        throw new MissingPayloadException();
                    }
                    settype($payload[$index], $type);
                    $parameters[] = $payload[$index];
                }
                catch (ValueError $_)
                {
                    $json = json_decode($payload[$index]);
                    $classParam = new $type($json);
                    $parameters[] = $classParam;
                }
            }
        }
        catch (ReflectionException $_)
        {
            throw new ClassNotFoundException();
        }

        return $parameters;
    }
}