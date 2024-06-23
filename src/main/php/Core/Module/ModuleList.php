<?php declare(strict_types = 1);

namespace Mys\Core\Module;

class ModuleList
{
    /**
     * @var ModuleLoader
     */
    private ModuleLoader $moduleLoader;

    /**
     * @param ModuleLoader $moduleLoader
     */
    public function __construct(ModuleLoader $moduleLoader)
    {
        $this->moduleLoader = $moduleLoader;
    }

    /**
     * @return string[]
     */
    public function get(): array
    {
        $modules = explode("\n", $this->moduleLoader->load());

        $removedEmptyModules = array_filter($modules);
        $trimmedModules = array_map(function ($module) {
            return trim($module);
        }, $removedEmptyModules);
        return [...$trimmedModules];
    }
}