<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Application\Application;
use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Logging\PrintLogger;

class Cli
{
    /**
     * @param string[] $arguments
     *
     * @return void
     */
    public static function main(array $arguments): void
    {
        require "../../../vendor/autoload.php";

        $functionCall = $arguments[1];
        $normalisedFunctionCall = str_replace(".", "\\", $functionCall);
        [$injectionToken, $function] = explode("::", $normalisedFunctionCall);
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
            $class = $injector->get($injectionToken);
            $class->$function();
        }
        catch (ClassNotFoundException|CyclicDependencyDetectedException $e)
        {
            $logger->error($e);
        }
    }
}

Cli::main($_SERVER["argv"]);