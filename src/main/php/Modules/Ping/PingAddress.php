<?php declare(strict_types = 1);

namespace Mys\Modules\Ping;

class PingAddress
{
    /**
     * @var string
     */
    private string $street;

    /**
     * @var string
     */
    private string $postalCode;

    /**
     * @param mixed $rawData
     */
    public function __construct(mixed $rawData)
    {
        $this->street = $rawData->street;
        $this->postalCode = $rawData->postalCode;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }
}