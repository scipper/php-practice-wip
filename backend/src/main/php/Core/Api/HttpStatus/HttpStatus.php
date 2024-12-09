<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus;

interface HttpStatus
{
    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return string
     */
    public function getStatusText(): string;

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string;
}