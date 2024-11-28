<?php declare(strict_types = 1);

namespace Mys\SystemTests\SysLogger;

use Mys\Core\Logging\Clock;

class MockClock implements Clock {

    private int $current = 0;

    /**
     * @return string
     */
    public function microtime(): string {
        return "2024-11-28 11:14:44.10000" . $this->current++;
    }
}