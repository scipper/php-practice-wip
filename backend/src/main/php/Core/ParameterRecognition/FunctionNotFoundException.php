<?php declare(strict_types = 1);

namespace Mys\Core\ParameterRecognition;

use Exception;

class FunctionNotFoundException extends Exception
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct("FunctionNotFoundException");
    }
}