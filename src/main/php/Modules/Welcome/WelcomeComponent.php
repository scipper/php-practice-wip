<?php declare(strict_types = 1);

namespace Mys\Modules\Welcome;

use Exception;
use Mys\Core\Logging\Logger;

class WelcomeComponent {
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    /**
     * @return string[]
     */
    public function printWelcomeMessage(): array {
        $response = <<<EOL
####################################
# Project: PHPInjection            #
# Version: 1.0.0                   #
#                                  #
####################################
EOL;
        foreach (explode("\n", $response) as $line) {
            $this->logger->info($line);
        }

        $this->logger->exception(new Exception("BLUB"));

        return [
            "message" => $response,
        ];
    }
}