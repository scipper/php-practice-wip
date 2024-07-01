<?php declare(strict_types = 1);

namespace Mys\Modules\Welcome;

class WelcomeComponent
{
    /**
     * @return string
     */
    public function printWelcomeMessage(): string
    {
        $response = "####################################\n";
        $response .= "# Project: PHPInjection            #\n";
        $response .= "# Version: 1.0.0                   #\n";
        $response .= "#                                  #\n";
        $response .= "####################################\n";

        return $response;
    }
}