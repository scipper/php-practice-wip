<?php declare(strict_types = 1);

namespace Mys\Modules\Ping\Request;

class PingRequest
{
    /**
     * @var string|null
     */
    private ?string $firstName;

    /**
     * @var string|null
     */
    private ?string $lastName;

    /**
     * @var PingAddress|null
     */
    private ?PingAddress $address;

    /**
     * @param mixed $rawData
     */
    public function __construct(mixed $rawData)
    {
        if (property_exists($rawData, "firstName"))
        {
            $this->firstName = $rawData->firstName;
        }
        if (property_exists($rawData, "lastName"))
        {
            $this->lastName = $rawData->lastName;
        }
        if (property_exists($rawData, "address"))
        {
            if (is_null($rawData->address))
            {
                $this->address = $rawData->address;
            }
            else
            {
                $this->address = new PingAddress($rawData->address);
            }
        }
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