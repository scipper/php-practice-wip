<?php declare(strict_types = 1);

namespace Mys\Modules\Ping;

use Mys\Core\Logging\Logger;
use Mys\Modules\Ping\Request\PingRequest;

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

    /**
     * @param PingRequest $request
     *
     * @return void
     */
    public function pingObject(PingRequest $request): void
    {
        print_r($request);
    }

    /**
     * @param int $id
     * @param PingRequest $request
     *
     * @return void
     */
    public function pingUpdateObject(int $id, PingRequest $request): void
    {
        print_r($id);
        print_r("\n");
        print_r($request);
        print_r("\n");
    }
}