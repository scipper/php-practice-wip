<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Application\Application;
use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Logging\PrintLogger;
use Mys\Modules\Welcome\WelcomeComponent;

class Router
{
    public static function main(): void
    {
        require "../../../vendor/autoload.php";

        $logger = new PrintLogger();
        $injector = new DependencyInjector($logger);
        $moduleListText = file_get_contents("./module-list.txt");
        $moduleList = [];
        if ($moduleListText)
        {
            array_push($moduleList, ...explode("\n", $moduleListText));
        }
        $application = new Application($injector, $moduleList, $logger);
        $application->init();

        try
        {
            /**
             * @var WelcomeComponent $class
             */
            $class = $injector->get(WelcomeComponent::class);
            $class->printWelcomeMessage();
        }
        catch (ClassNotFoundException|CyclicDependencyDetectedException $e)
        {
            $logger->error($e);
        }
    }
}

Router::main();
