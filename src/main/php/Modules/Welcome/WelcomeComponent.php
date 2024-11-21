<?php declare(strict_types = 1);

namespace Mys\Modules\Welcome;

use Mys\Core\Logging\Logger;

class WelcomeComponent
{
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function printWelcomeMessage(): string
    {
        $response = <<<EOL
####################################
# Project: PHPInjection            #
# Version: 1.0.0                   #
#                                  #
####################################
EOL;

        $this->logger->info($response);

        return $response;
    }
}