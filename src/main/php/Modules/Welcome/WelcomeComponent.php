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
     * @return void
     */
    public function printWelcomeMessage(): void
    {
        $this->logger->info("####################################");
        $this->logger->info("# Project: PHPInjection            #");
        $this->logger->info("# Version: 1.0.0                   #");
        $this->logger->info("#                                  #");
        $this->logger->info("####################################");
    }
}