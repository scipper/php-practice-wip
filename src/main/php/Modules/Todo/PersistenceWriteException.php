<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;

class PersistenceWriteException extends Exception {
    /**
     *
     */
    public function __construct() {
        parent::__construct("Failed to persist data");
    }
}