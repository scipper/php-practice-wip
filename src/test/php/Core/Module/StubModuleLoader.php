<?php declare(strict_types = 1);

namespace Mys\Core\Module;

class StubModuleLoader implements ModuleLoader
{
    private string|array $modules;

    public function load(): string|array
    {
        return $this->modules;
    }

    public function doReturn(string|array $modules)
    {
        $this->modules = $modules;
    }
}