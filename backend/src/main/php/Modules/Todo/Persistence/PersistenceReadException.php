<?php declare(strict_types = 1);

namespace Mys\Modules\Todo\Persistence;

use Exception;

class PersistenceReadException extends Exception {
    public function __construct() {
        parent::__construct("Failed to read data: See log for more details.");
    }
}