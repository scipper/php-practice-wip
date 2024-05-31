<?php

namespace Mys\Core\Module;

interface Module
{
    /**
     * @return string[]
     */
    public function getClasses(): array;

    /**
     * @return string[]
     */
    public function getModules(): array;
}