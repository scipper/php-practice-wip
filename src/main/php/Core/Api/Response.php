<?php declare(strict_types = 1);

namespace Mys\Core\Api;

use JsonSerializable;
use Mys\Core\Api\HttpStatus\HttpStatus;
use stdClass;

class Response implements JsonSerializable
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
     * @var string|null
     */
    private ?string $errorMessage;

    /**
     * @var string|null
     */
    private ?string $content;

    /**
     * @var string
     */
    private string $contentType;

    /**
     * @param HttpStatus|null $exception
     * @param mixed|null $content
     */
    public function __construct(HttpStatus $exception = null, mixed $content = null, string $contentType = "application/json")
    {
        $this->statusCode = 200;
        $this->statusText = "Ok";
        $this->errorMessage = null;
        $this->content = null;
        $this->contentType = $contentType;
        if ($exception)
        {
            $this->statusCode = $exception->getStatusCode();
            $this->statusText = $exception->getStatusText();
            $this->errorMessage = $exception->getErrorMessage();
        }
        if (gettype($content) === "string")
        {
            $this->content = $content;
        }
        if (gettype($content) === "integer" || gettype($content) === "double")
        {
            $this->content = strval($content);
        }
        if (gettype($content) === "boolean")
        {
            $this->content = $content ? "true" : "false";
        }
        if (gettype($content) === "array")
        {
            $this->content = json_encode($content);
        }
        if (gettype($content) === "object")
        {
            $this->content = json_encode($content);
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
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        $json = new stdClass();
        $json->statusCode = $this->statusCode;
        $json->statusText = $this->statusText;
        $json->errorMessage = $this->errorMessage;
        $json->contentType = $this->contentType;

        return $json;
    }
}