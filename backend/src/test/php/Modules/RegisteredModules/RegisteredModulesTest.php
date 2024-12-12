<?php declare(strict_types = 1);

namespace Mys\Modules\RegisteredModules;

use Mys\Core\Module\ModuleList;
use Mys\Core\Module\StubModuleLoader;
use PHPUnit\Framework\TestCase;

class RegisteredModulesTest extends TestCase {

    /**
     * @return void
     */
    public function test_module_has_get_registered_modules_endpoint() {
        $module = new RegisteredModulesModule();

        $endpoints = $module->getEndpoints();

        $this->assertCount(1, $endpoints);
        $this->assertEquals("/registeredmodules", $endpoints[0]->getPath());
        $this->assertEquals("get", $endpoints[0]->getMethod());
        $this->assertEquals("application/json", $endpoints[0]->getProduces());
    }

    /**
     * @return void
     */
    public function test_returns_list_of_registered_modules_from_module_list() {
        $moduleLoader = new StubModuleLoader();
        $moduleList = new ModuleList($moduleLoader);
        $controller = new RegisteredModulesController($moduleList);
        $moduleLoader->doReturn(["moduleA", "moduleB"]);

        $list = $controller->getRegisteredModulesList();

        $this->assertEquals(["moduleA", "moduleB"], $list);
    }
}
