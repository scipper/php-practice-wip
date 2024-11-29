<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use Exception;

class NullReturnsFromPersistenceException extends Exception {
    /**
     *
     */
    public function __construct() {
        parent::__construct("Null returned from persistence");
    }
}