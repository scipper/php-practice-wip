<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

use Exception;

class ClassAlreadyRegisteredException extends Exception {
    /**
     * @param string $classAlreadyRegistered
     */
    public function __construct(string $classAlreadyRegistered) {
        parent::__construct("Class $classAlreadyRegistered already exists");
    }
}