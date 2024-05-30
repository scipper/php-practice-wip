<?php

namespace Mys\Core\Application;

use Mys\Core\Injection\ClassAlreadyRegisteredException;
use Mys\Core\Injection\Injector;
use Mys\Core\Module\Module;

class Application
{
    /**
     * @var string[] $classList
     */
    private array $classList;

    /**
     * @var Injector $injector
     */
    private Injector $injector;

    /**
     * @param Injector $injector
     * @param string[] $classList
     */
    public function __construct(Injector $injector, array $classList)
    {
        $this->classList = $classList;
        $this->injector = $injector;
    }

    /**
     * @throws ClassAlreadyRegisteredException
     */
    public function init()
    {
        foreach ($this->classList as $class) {
            /**
             * @var Module $class
             */
            $module = new $class();
            foreach ($module->getClasses() as $innerClass) {
                $this->injector->register($innerClass);
            }
        }
    }
}