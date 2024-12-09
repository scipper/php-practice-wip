<?php declare(strict_types = 1);

namespace Mys\Core\Logging;

interface Clock {
    /**
     * @return string
     */
    public function microtime(): string;
}