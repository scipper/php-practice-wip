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
        $modulesFromLoader = $this->moduleLoader->load();
        if (is_array($modulesFromLoader)) {
            $modules = $modulesFromLoader;
        } else {
            $modules = explode("\n", $modulesFromLoader);
        }

        $removedEmptyModules = array_filter($modules);
        $trimmedModules = array_map(function ($module) {
            return trim($module);
        }, $removedEmptyModules);
        $uniqueModules = array_unique($trimmedModules);
        return [...$uniqueModules];
    }
}