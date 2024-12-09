<?php declare(strict_types = 1);

namespace Mys\SystemTests\ModuleLoader;

use Mys\Core\Module\FileModuleLoader;
use Mys\Core\Module\ModuleList;
use PHPUnit\Framework\TestCase;

class FileModuleLoaderTest extends TestCase {
    /**
     * @return void
     */
    public function test_loads_modules_out_of_file() {
        $moduleLoader = new FileModuleLoader(__DIR__ . "/test-module-list.txt");
        $moduleList = new ModuleList($moduleLoader);
        $modules = $moduleList->get();
        $this->assertSame(["Mys\\SystemTests\\ModuleLoader\\SomeModule"], $modules);
    }
}
