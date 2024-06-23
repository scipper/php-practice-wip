<?php declare(strict_types = 1);

namespace Mys;

use JetBrains\PhpStorm\NoReturn;
use Mys\Core\Api\HttpRouteRegister;
use Mys\Core\Api\Request;
use Mys\Core\Api\RouteRegister;
use Mys\Core\Application\Application;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Injection\Injector;
use Mys\Core\Logging\Logger;
use Mys\Core\Logging\SysLogger;
use Mys\Core\Module\FileModuleLoader;
use Mys\Core\Module\ModuleList;
use Mys\Core\ParameterRecognition\ParameterRecognition;

use function error_log;
use function microtime;

require "../../../vendor/autoload.php";

class Router
{
    /**
     * @var Injector
     */
    private Injector $injector;

    /**
     * @var RouteRegister
     */
    private RouteRegister $routeRegister;

    /**
     * @var ModuleList
     */
    private ModuleList $moduleList;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param Injector $i
     * @param RouteRegister $rr
     * @param ModuleList $ml
     * @param Logger $l
     */
    public function __construct(Injector $i, RouteRegister $rr, ModuleList $ml, Logger $l)
    {
        $this->injector = $i;
        $this->routeRegister = $rr;
        $this->moduleList = $ml;
        $this->logger = $l;
    }

    /**
     * @return void
     */
    #[NoReturn] public function route(): void
    {
        $start = microtime(true);

        $application = new Application($this->injector, $this->moduleList->get(), $this->logger, $this->routeRegister);
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
        $response = $this->routeRegister->routeTo($request);

        ob_start();
        ob_clean();
        header_remove();

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: {$response->getContentType()}; charset=utf-8");
        http_response_code($response->getStatusCode());
        $content = $response->getContent();
        if ($content)
        {
            echo($content);
        }
        if ($response->getStatusCode() >= 400)
        {
            echo json_encode($response);
        }

        $end = microtime(true);
        error_log((string)($end - $start));
        exit();
    }

    /**
     * @return void
     */
    #[NoReturn] public static function main(): void
    {
        $logger = new SysLogger();
        $injector = new DependencyInjector($logger);
        $parameterRecognition = new ParameterRecognition();
        $routeRegister = new HttpRouteRegister($parameterRecognition, $injector);
        $moduleList = new ModuleList(new FileModuleLoader("./module-list.txt"));

        $router = new Router($injector, $routeRegister, $moduleList, $logger);
        $router->route();
    }
}

Router::main();
