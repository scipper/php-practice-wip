<?php declare(strict_types = 1);

namespace Mys\Core\Api;

class Endpoint
{
    private string $path;

    private string $class;

    private string $function;

    private string $method;

    /**
     * @param string $class
     * @param string $function
     */
    public function __construct(string $class, string $function)
    {
        $this->class = $class;
        $this->function = $function;
        $this->path = "";
        $this->method = "get";
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = strtolower($path);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getFunction(): string
    {
        return $this->function;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = strtolower($method);
    }
}