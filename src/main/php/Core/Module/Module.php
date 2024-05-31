<?php

namespace Mys\Core\Module;

interface Module
{
    /**
     * @return string[]
     */
    public function getClasses(): array;
}