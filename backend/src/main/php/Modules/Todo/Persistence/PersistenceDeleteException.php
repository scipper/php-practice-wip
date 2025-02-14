<?php declare(strict_types = 1);

namespace Mys\Modules\Todo\Persistence;

use Exception;

class PersistenceDeleteException extends Exception {
    /**
     *
     */
    public function __construct() {
        parent::__construct("Failed to delete data");
    }
}