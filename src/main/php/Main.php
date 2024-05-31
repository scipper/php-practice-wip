<?php

namespace Mys;

use Mys\Core\Application\Application;
use Mys\Core\Logging\PrintLogger;
use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Modules\Welcome\WelcomeComponent;

class Main
{
    public static function main(): void
    {
        require "../../../vendor/autoload.php";

        $logger = new PrintLogger();
        $injector = new DependencyInjector($logger);
        $moduleListText = file_get_contents("./module-list.txt");
        $moduleList = [];
        if($moduleListText) {
            array_push($moduleList, ...explode("\n", $moduleListText));
        }
        $application = new Application($injector, $moduleList, $logger);
        try
        {
            $application->init();
            /**
             * @var WelcomeComponent $welcomeComponent
             */
            $welcomeComponent = $injector->get(WelcomeComponent::class);

            $welcomeComponent->printWelcomeMessage();
        }
        catch (ClassNotFoundException|CyclicDependencyDetectedException $e)
        {
            $logger->error($e);
        }
    }
}

Main::main();
