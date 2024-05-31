<?php

namespace Mys;

use Mys\Core\Application\Application;
use Mys\Core\Application\Logging\PrintLogger;
use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\Injector;
use Mys\Modules\Test\TestComponent;
use Mys\Modules\Test\TestModule;

class Main
{
    public static function main(): void
    {
        require "../../../vendor/autoload.php";

        $logger = new PrintLogger();
        $injector = new Injector($logger);
        $moduleList = [TestModule::class];
        $application = new Application($injector, $moduleList, $logger);
        try {
            $application->init();
            /**
             * @var TestComponent $testComponent
             */
            $testComponent = $injector->get("ASD");

            $testComponent->greeting();
        } catch (ClassNotFoundException $e) {
            $logger->error($e);
        }
    }
}

Main::main();
