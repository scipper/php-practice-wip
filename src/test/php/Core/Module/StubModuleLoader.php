<?php declare(strict_types = 1);

namespace Mys\Core\Module;

class StubModuleLoader implements ModuleLoader
{
    private string $modules;

    public function load(): string
    {
        return $this->modules;
    }

    public function doReturn(string $modules)
    {
        $this->modules = $modules;
    }
}