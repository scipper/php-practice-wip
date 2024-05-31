<?php

namespace Mys\Core\Injection;

interface Injector
{
    /**
     * @param string $class
     *
     * @return void
     */
    public function register(string $class): void;

    /**
     * @param string $class
     *
     * @return mixed
     * @throws CyclicDependencyDetectedException
     * @throws ClassNotFoundException
     */
    public function get(string $class): mixed;
}