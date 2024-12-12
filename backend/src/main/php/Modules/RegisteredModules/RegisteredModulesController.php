<?php declare(strict_types = 1);

namespace Mys\Modules\RegisteredModules;

use Mys\Core\Module\ModuleList;

class RegisteredModulesController {

    /**
     * @var ModuleList
     */
    private ModuleList $moduleList;

    /**
     * @param ModuleList $moduleList
     */
    public function __construct(ModuleList $moduleList) {
        $this->moduleList = $moduleList;
    }

    /**
     * @return string[]
     */
    public function getRegisteredModulesList(): array {
        return $this->moduleList->get();
    }

}