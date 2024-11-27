<?php declare(strict_types = 1);

namespace Mys\Core;

use Exception;

class ClassNotFoundException extends Exception {
    /**
     *
     */
    public function __construct() {
        parent::__construct("ClassNotFoundException");
    }
}