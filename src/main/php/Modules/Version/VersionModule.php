<?php declare(strict_types = 1);

namespace Mys\Modules\Version;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class VersionModule extends Module {
    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array {
        $getVersion = new Endpoint(VersionController::class, "getVersion");
        $getVersion->setPath("/version");
        $getVersion->setProduces("text/plain");
        return [$getVersion];
    }
}