<?php declare(strict_types = 1);

namespace Mys\Core\Api;

class Request
{
    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var string|null
     */
    private string|null $payload;

    /**
     * @var string[]
     */
    private array $headers;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = strtolower($path);
        $this->method = "get";
        $this->payload = null;
        $this->headers = [
            "accept" => "application/json",
            "content-type" => "application/json",
        ];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return void
     */
    public function setMethod(string $method): void
    {
        $this->method = strtolower($method);
    }

    /**
     * @return string|null
     */
    public function getPayload(): ?string
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     *
     * @return void
     */
    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setHeader(string $key, string $value): void
    {
        $this->headers[strtolower($key)] = strtolower($value);
    }
}