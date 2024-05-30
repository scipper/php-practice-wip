<?php

namespace Mys\test;

interface Module
{

    /**
     * @return string[]
     */
    public function getClasses(): array;
}