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

class Router {
    /**
     * @var RouteRegister
     */
    private RouteRegister $routeRegister;

    /**
     * @param string $logsFolder
     * @param string $moduleListFile
     *
     * @return false|string
     * @throws ClassAlreadyRegisteredException
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public static function main(string $logsFolder, string $moduleListFile): false|string {
        $injector = new DependencyInjector();
        $clock = new DateTimeClock();
        $injector->register(Logger::class, function () use ($logsFolder, $clock) {
            return new SysLogger($logsFolder, $clock);
        });
        $parameterRecognition = new ParameterRecognition();
        $routeRegister = new HttpRouteRegister($parameterRecognition, $injector);
        $moduleList = new ModuleList(new FileModuleLoader($moduleListFile));

        $application = new Application($injector, $moduleList->get(), $routeRegister);
        $application->init();

        $router = new Router($routeRegister);
        return $router->route(self::getRequest());
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

    /**
     * @param RouteRegister $routeRegister
     */
    public function __construct(RouteRegister $routeRegister) {
        $this->routeRegister = $routeRegister;
    }

    /**
     * @param $request
     *
     * @return false|string
     */
    public function route($request): false|string {
        $response = $this->routeRegister->processRequest($request);

        return $this->respond($response);
    }

    /**
     * @param Response $response
     *
     * @return false|string
     */
    public function respond(Response $response): false|string {
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
            echo json_encode($response, JSON_PRETTY_PRINT);
        }

        return ob_get_clean();
    }
}
