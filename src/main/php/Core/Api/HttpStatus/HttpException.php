<?php declare(strict_types = 1);

namespace Mys\Core\Api\HttpStatus;

use Exception;
use TypeError;

class HttpException extends Exception implements HttpStatus
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
     * @var string
     */
    private string $errorMessage;

    /**
     * @param int $statusCode
     * @param string $statusText
     * @param Exception|null $exception
     */
    public function __construct(int $statusCode, string $statusText, Exception|TypeError $exception = null)
    {
        parent::__construct("HttpException");
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
        $this->errorMessage = "Http Exception";
        if ($exception)
        {
            $this->errorMessage = $exception->getMessage();
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

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}