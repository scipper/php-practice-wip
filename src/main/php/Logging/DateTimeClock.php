<?php declare(strict_types = 1);

namespace Mys\Logging;

use DateTime;
use Mys\Core\Logging\Clock;

class DateTimeClock implements Clock {

    /**
     * @return string
     */
    public function microtime(): string {
        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        return $now->format("Y-m-d H:i:s.u");
    }
}