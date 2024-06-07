<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Api\HttpRouteRegister;
use Mys\Core\Api\Request;
use Mys\Core\Application\Application;
use Mys\Core\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Logging\SysLogger;
use Mys\Core\ParameterRecognition\FunctionNotFoundException;
use Mys\Core\ParameterRecognition\MissingPayloadException;
use Mys\Core\ParameterRecognition\ParameterRecognition;

class Router
{
    /**
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     * @throws CyclicDependencyDetectedException
     * @throws ClassNotFoundException
     */
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

        $logger = new SysLogger();
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

        $payload = file_get_contents("php://input");
        $path = "/";
        if (array_key_exists("REDIRECT_URL", $_SERVER))
        {
            $path = $_SERVER["REDIRECT_URL"];
        }
        $request = new Request($path);
        $request->setMethod($_SERVER["REQUEST_METHOD"]);
        $request->setPayload($payload);
        $response = $routeRegister->routeTo($request);

        ob_start();
        ob_clean();
        header_remove();

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: {$response->getContentType()}; charset=utf-8");
        http_response_code($response->getStatusCode());
        $content = $response->getContent();
        if ($content)
        {
            echo json_encode($content);
        }
        if ($response->getStatusCode() >= 400)
        {
            echo json_encode($response);
        }
        exit();
    }
}

Router::main();
