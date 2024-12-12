<?php declare(strict_types = 1);

namespace Mys\Modules\RegisteredModules;

use Mys\Core\Api\Endpoint;
use Mys\Core\Module\Module;

class RegisteredModulesModule extends Module {

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array {
        $list = new Endpoint(RegisteredModulesController::class, "getRegisteredModulesList");
        $list->setPath("/registeredmodules");

        return [$list];
    }
}