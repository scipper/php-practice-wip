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
     * @return string
     */
    public function pingString(string $request): string
    {
        return $request;
    }

    /**
     * @return void
     */
    public function ping(): void
    {
        $this->logger->info("pong");
    }

    public function pingObject(PingRequest $request): PingRequest
    {
        return $request;
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