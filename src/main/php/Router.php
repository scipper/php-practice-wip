<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Application\Application;
use Mys\Core\Injection\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Logging\PrintLogger;
use Mys\Core\ParameterRecognition\ParameterRecognition;

class Router
{
    public static function main(): void
    {
        require "../../../vendor/autoload.php";
        echo "<pre>";
        echo "GET";
        var_dump($_GET);
        echo "POST";
        var_dump($_POST);
        echo "SERVER";
        var_dump($_SERVER);
        echo "REQUEST";
        var_dump($_REQUEST);
//        echo "</pre>";

        require "../../../vendor/autoload.php";

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

        [$injectionToken, $method] = explode("/", trim(str_replace(".", "\\", $_SERVER["REQUEST_URI"]), "/"));
        $payload = file_get_contents('php://input');

        try
        {
            $parameterList = $parameterRecognition->recognise($injectionToken, $method, $payload);
            $class = $injector->get($injectionToken);
            $class->$method(...$parameterList);
        }
        catch (ClassNotFoundException|CyclicDependencyDetectedException $e)
        {
            $logger->error($e);
        }
    }
}

Router::main();
