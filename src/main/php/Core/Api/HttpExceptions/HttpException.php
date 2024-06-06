<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpExceptions;

use Exception;

class HttpException extends Exception
{
    /**
     * @var int
     */
    protected int $statusCode;

    /**
     * @var string
     */
    protected string $statusText;

    /**
     * @param int $statusCode
     * @param string $statusText
     */
    public function __construct(int $statusCode, string $statusText)
    {
        parent::__construct("HttpException");
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
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
}