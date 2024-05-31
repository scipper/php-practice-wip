<?php declare(strict_types=1);

namespace Mys\Core\Application;

use Exception;

class ClassIsNotModuleException extends Exception
{
    public function __construct()
    {
        parent::__construct("ClassIsNotModuleException");
    }
}