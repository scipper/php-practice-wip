<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Api\HttpRouteRegister;
use Mys\Core\Application\Application;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Logging\PrintLogger;
use Mys\Core\ParameterRecognition\ParameterRecognition;

class Router
{
    public static function main(): void
    {
        require "../../../vendor/autoload.php";
//        echo "<pre>";
//        echo "GET";
//        var_dump($_GET);
//        echo "POST";
//        var_dump($_POST);
//        echo "SERVER";
//        var_dump($_SERVER);
//        echo "REQUEST";
//        var_dump($_REQUEST);
//        echo "</pre>";

        require "../../../vendor/autoload.php";

        $logger = new PrintLogger();
        $injector = new DependencyInjector($logger);
        $parameterRecognition = new ParameterRecognition();
        $routeRegister = new HttpRouteRegister($parameterRecognition, $injector);
        $moduleListText = file_get_contents("./module-list.txt");
        $moduleList = [];
        if ($moduleListText)
        {
            array_push($moduleList, ...explode("\n", $moduleListText));
        }
        $application = new Application($injector, $moduleList, $logger, $routeRegister);
        $application->init();

        $payload = file_get_contents('php://input');
        $path = "/";
        if (array_key_exists("REDIRECT_URL", $_SERVER))
        {
            $path = $_SERVER["REDIRECT_URL"];
        }
        $routeRegister->routeTo($path, $_SERVER["REQUEST_METHOD"], $payload);

//        [$injectionToken, $method] = explode("/", trim(str_replace(".", "\\", $_SERVER["REQUEST_URI"]), "/"));
//
//
//        try
//        {
//            $parameterList = $parameterRecognition->recognise($injectionToken, $method, $payload);
//            $class = $injector->get($injectionToken);
//            $class->$method(...$parameterList);
//        }
//        catch (ClassNotFoundException|CyclicDependencyDetectedException $e)
//        {
//            $logger->error($e);
//        }
    }
}

Router::main();
