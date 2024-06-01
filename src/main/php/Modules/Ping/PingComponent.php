<?php declare(strict_types = 1);

namespace Mys\Modules\Ping;

use Mys\Core\Logging\Logger;

class PingComponent
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
     * @param string $request
     *
     * @return void
     */
    public function pingString(string $request): void
    {
        $this->logger->info($request);
    }

    public function pingObject(PingRequest $request): void
    {
        print_r($request);
    }
}