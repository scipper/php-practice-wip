<?php declare(strict_types = 1);

namespace Mys\Core\Injection;

use Mys\Core\ClassNotFoundException;

interface Injector
{
    /**
     * @param string $injectionToken
     * @param string|null $class
     *
     * @return void
     */
    public function register(string $injectionToken, string $class = null): void;

    /**
     * @param string $injectionToken
     *
     * @return mixed
     * @throws CyclicDependencyDetectedException
     * @throws ClassNotFoundException
     */
    public function get(string $injectionToken): mixed;
}