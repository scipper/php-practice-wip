<?php declare(strict_types = 1);

namespace Mys\SystemTests\ModuleLoader;

use Mys\Core\Module\ModuleList;
use Mys\Core\Module\PHPFileModuleLoader;
use PHPUnit\Framework\TestCase;

class PHPFileModuleLoaderTest extends TestCase {
    public function test_loads_modules_from_php_file() {
        $moduleLoader = new PHPFileModuleLoader(__DIR__ . "/test-module-list.php");
        $moduleList = new ModuleList($moduleLoader);
        $modules = $moduleList->get();
        $this->assertSame([MockModule::class], $modules);
    }
}
