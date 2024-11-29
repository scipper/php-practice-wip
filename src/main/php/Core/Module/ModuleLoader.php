<?php declare(strict_types = 1);

namespace Mys\Core\Module;

interface ModuleLoader {
    /**
     * @return string|array
     */
    public function load(): string|array;
}