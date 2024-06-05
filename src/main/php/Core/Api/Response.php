<?php declare(strict_types = 1);

namespace Mys\Core\Api;

class Response
{
    public function __construct()
    {
    }

    public function getStatusCode(): int
    {
        return 200;
    }

    public function getStatusText()
    {
        return "Unsupported Media Type";
    }
}