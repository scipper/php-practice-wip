<?php declare(strict_types = 1);

namespace Mys\Core\Module;

use PHPUnit\Framework\TestCase;

class ModuleLoaderTest extends TestCase
{
    /**
     * @var ModuleList
     */
    private ModuleList $moduleList;

    /**
     * @var StubModuleLoader
     */
    private StubModuleLoader $moduleLoader;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->moduleLoader = new StubModuleLoader();
        $this->moduleList = new ModuleList($this->moduleLoader);
    }

    /**
     * @return void
     */
    public function test_returns_a_given_string_as_element()
    {
        $this->moduleLoader->doReturn("module");

        $moduleList = $this->moduleList->get();

        $this->assertSame(["module"], $moduleList);
    }

    /**
     * @return void
     */
    public function test_splits_two_modules_by_new_line_into_two_elements()
    {
        $this->moduleLoader->doReturn("moduleA\nmoduleB");

        $moduleList = $this->moduleList->get();

        $this->assertSame(["moduleA", "moduleB"], $moduleList);
    }

    /**
     * @return void
     */
    public function test_removes_empty_lines()
    {
        $this->moduleLoader->doReturn("moduleA\n\nmoduleB");

        $moduleList = $this->moduleList->get();

        $this->assertSame(["moduleA", "moduleB"], $moduleList);
    }

    /**
     * @return void
     */
    public function test_removes_whitespace_before_module_name()
    {
        $this->moduleLoader->doReturn("moduleA\n moduleB");

        $moduleList = $this->moduleList->get();

        $this->assertSame(["moduleA", "moduleB"], $moduleList);
    }

    /**
     * @return void
     */
    public function test_removes_whitespace_after_module_name()
    {
        $this->moduleLoader->doReturn("moduleA \nmoduleB");

        $moduleList = $this->moduleList->get();

        $this->assertSame(["moduleA", "moduleB"], $moduleList);
    }

    /**
     * @return void
     */
    public function test_return_a_module_only_once()
    {
        $this->moduleLoader->doReturn("moduleA\nmoduleA");

        $moduleList = $this->moduleList->get();

        $this->assertSame(["moduleA"], $moduleList);
    }

}
