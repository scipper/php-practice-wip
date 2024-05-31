<?php

namespace Mys\Modules\Welcome;

use Mys\Core\Application\Logging\Logger;

class WelcomeComponent
{
    public function __construct(Logger $logger)
    {
    }

    public function printWelcomeMessage(): void
    {
        print_r("####################################\n");
        print_r("# Project: PHPInjection            #\n");
        print_r("# Version: 1.0.0                   #\n");
        print_r("#                                  #\n");
        print_r("####################################\n");
    }
}