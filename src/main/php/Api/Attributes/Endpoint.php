<?php declare(strict_types = 1);

namespace Mys\Api\Attributes;

use Attribute;

#[Attribute]
class Endpoint
{
    public function __construct(string $path)
    {
        print_r($path . "\n");
    }
}