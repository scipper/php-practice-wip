<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Application\Application;
use Mys\Core\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Logging\PrintLogger;
use Mys\Core\ParameterRecognition\ParameterRecognition;

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

        array_shift($arguments);
        $functionCall = array_shift($arguments);
        $payload = $arguments;
        $normalisedFunctionCall = str_replace(".", "\\", $functionCall);
        [$injectionToken, $function] = explode("::", $normalisedFunctionCall);
        $logger = new PrintLogger();
        $injector = new DependencyInjector($logger);
        $parameterRecognition = new ParameterRecognition();
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
            $parameterList = $parameterRecognition->recognise($injectionToken, $function, ...$payload);
            $class = $injector->get($injectionToken);
            $class->$function(...$parameterList);
        }
        catch (ClassNotFoundException|CyclicDependencyDetectedException $e)
        {
            $logger->error($e);
        }
    }
}

Cli::main($_SERVER["argv"]);