<?php declare(strict_types = 1);

namespace Mys\Modules\Ping;

class PingRequest
{
    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var PingAddress
     */
    private PingAddress $address;

    /**
     * @param mixed $rawData
     */
    public function __construct(mixed $rawData)
    {
        $this->firstName = $rawData->firstName;
        $this->lastName = $rawData->lastName;
        $this->address = new PingAddress($rawData->address);
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return PingAddress
     */
    public function getAddress(): PingAddress
    {
        return $this->address;
    }
}