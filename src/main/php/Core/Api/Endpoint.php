<?php declare(strict_types = 1);

namespace Mys\Core\Api;

class Endpoint
{
    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $class;

    /**
     * @var string
     */
    private string $function;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var string
     */
    private string $produces;

    /**
     * @var string
     */
    private string $consumes;

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
        $this->produces = "application/json";
        $this->consumes = "application/json";
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = strtolower($path);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
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
     * @return string
     */
    public function getProduces(): string
    {
        return $this->produces;
    }

    /**
     * @param string $contentType
     *
     * @return void
     */
    public function setProduces(string $contentType): void
    {
        $this->produces = strtolower($contentType);
    }

    /**
     * @param string $consumes
     *
     * @return void
     */
    public function setConsumes(string $consumes): void
    {
        $this->consumes = strtolower($consumes);
    }

    /**
     * @return string
     */
    public function getConsumes(): string
    {
        return $this->consumes;
    }
}