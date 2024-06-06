<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use Mys\Core\Api\HttpExceptions\HttpException;

class Response
{
    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var string
     */
    private string $statusText;

    /**
     * @param HttpException|null $exception
     */
    public function __construct(HttpException $exception = null)
    {
        $this->statusCode = 200;
        $this->statusText = "Ok";
        if ($exception)
        {
            $this->statusCode = $exception->getStatusCode();
            $this->statusText = $exception->getStatusText();
        }
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