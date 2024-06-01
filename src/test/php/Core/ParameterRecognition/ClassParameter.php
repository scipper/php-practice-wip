<?php declare(strict_types = 1);

namespace Mys\Core\ParameterRecognition;

class ClassParameter
{
    /**
     * @var string $type
     */
    private string $type;

    /**
     * @param mixed $rawData
     */
    public function __construct(mixed $rawData)
    {
        $this->type = $rawData->type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}