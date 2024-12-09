<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus\Success;

use Mys\Core\Api\HttpStatus\HttpStatus;

class Ok implements HttpStatus
{
    /**
     * @var int
     */
    protected int $statusCode;

    /**
     * @var string
     */
    protected string $statusText;

    public function __construct()
    {
        $this->statusCode = 200;
        $this->statusText = "Ok";
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getStatusText(): string
    {
        return $this->statusText;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return null;
    }
}