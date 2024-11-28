<?php declare(strict_types = 1);

namespace Mys;

use Mys\Core\Api\HttpRouteRegister;
use Mys\Core\Api\Request;
use Mys\Core\Api\Response;
use Mys\Core\Api\RouteRegister;
use Mys\Core\Application\Application;
use Mys\Core\ClassNotFoundException;
use Mys\Core\Injection\ClassAlreadyRegisteredException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\DependencyInjector;
use Mys\Core\Logging\Logger;
use Mys\Core\Module\FileModuleLoader;
use Mys\Core\Module\ModuleList;
use Mys\Core\ParameterRecognition\ParameterRecognition;
use Mys\Logging\DateTimeClock;
use Mys\Logging\SysLogger;

require "../../../vendor/autoload.php";

class Router {
    /**
     * @var RouteRegister
     */
    private RouteRegister $routeRegister;

    /**
     * @param RouteRegister $routeRegister
     */
    public function __construct(RouteRegister $routeRegister) {
        $this->routeRegister = $routeRegister;
    }

    /**
     * @param $request
     *
     * @return void
     */
    public function route($request): void {
        $response = $this->routeRegister->processRequest($request);

        $this->respond($response);
    }

    /**
     * @param Response $response
     *
     * @return void
     */
    public function respond(Response $response): void {
        ob_start();
        ob_clean();
        header_remove();

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: {$response->getContentType()}; charset=utf-8");
        http_response_code($response->getStatusCode());
        $content = $response->getContent();
        if ($content) {
            echo($content);
        }
        if ($response->getStatusCode() >= 400) {
            echo json_encode($response);
        }

        exit();
    }

    /**
     * @return void
     * @throws ClassAlreadyRegisteredException
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public static function main(): void {
        $injector = new DependencyInjector();
        $clock = new DateTimeClock();
        $injector->register(Logger::class, function () use ($clock) {
            return new SysLogger(__DIR__ . "/../../../logs", $clock);
        });
        $parameterRecognition = new ParameterRecognition();
        $routeRegister = new HttpRouteRegister($parameterRecognition, $injector);
        $moduleList = new ModuleList(new FileModuleLoader("../resources/Modules/module-list.txt"));

        $application = new Application($injector, $moduleList->get(), $routeRegister);
        $application->init();

        $router = new Router($routeRegister);
        $router->route(self::getRequest());
    }

    /**
     * @return Request
     */
    private static function getRequest(): Request {
        $payload = file_get_contents("php://input");
        $path = "/";
        if (array_key_exists("REDIRECT_URL", $_SERVER)) {
            $path = str_replace("api/", "", $_SERVER["REDIRECT_URL"]);
        }
        $request = new Request($path);
        $request->setMethod($_SERVER["REQUEST_METHOD"]);
        $request->setPayload($payload);
        return $request;
    }
}

Router::main();
