<?php

namespace Mys;

use Mys\Core\Application\Application;
use Mys\Core\Injection\ClassAlreadyRegisteredException;
use Mys\Core\Injection\Injector;
use Mys\Modules\Test\TestModule;

class Main
{
    /**
     * @throws ClassAlreadyRegisteredException
     */
    public static function main(): void
    {
        echo "Hello World!\n";
        $injector = new Injector();
        $classList = [TestModule::class];
        $application = new Application($injector, $classList);
        $application->init();
    }
}

try
{
    Main::main();
}
catch (ClassAlreadyRegisteredException $e)
{
    echo $e->getMessage();
}
