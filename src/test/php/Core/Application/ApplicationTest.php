<?php

namespace Mys\Core\Application;

use Mys\Core\Injection\ClassAlreadyRegisteredException;
use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\DummyClass;
use Mys\Core\Injection\Injector;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ApplicationTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws ClassAlreadyRegisteredException
     * @throws ClassNotFoundException
     */
    public function test_registers_a_class_of_a_module()
    {
        $injector = new Injector();
        $moduleList = [DummyModule::class];
        $application = new Application($injector, $moduleList);

        $application->init();
        $injection = $injector->get(DummyComponent::class);

        $this->assertInstanceOf(DummyComponent::class, $injection);
    }
}
