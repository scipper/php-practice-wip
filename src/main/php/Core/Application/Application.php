<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Error;
use Mys\Core\Api\RouteRegister;
use Mys\Core\Injection\Injector;
use Mys\Core\Logging\Logger;
use Mys\Core\Module\Module;

class Application {
    /**
     * @var string[]
     */
    private array $moduleList;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var RouteRegister
     */
    private RouteRegister $routeRegister;

    /**
     * @var Injector
     */
    private Injector $injector;

    /**
     * @param Logger $logger
     * @param array $moduleList
     * @param RouteRegister $routeRegister
     * @param Injector $injector
     */
    public function __construct(Logger $logger, array $moduleList, RouteRegister $routeRegister, Injector $injector) {
        $this->moduleList = $moduleList;
        $this->logger = $logger;
        $this->routeRegister = $routeRegister;
        $this->injector = $injector;
    }

    /**
     * @return void
     */
    public function init(): void {
        $this->processModuleList($this->moduleList);
    }

    /**
     * @param array $moduleList
     *
     * @return void
     */
    private function processModuleList(array $moduleList): void {
        foreach ($moduleList as $class) {
            try {
                $module = new $class();

                if (!($module instanceof Module)) {
                    $this->logger->exception(new ClassIsNotModuleException());
                } else {
                    foreach ($module->getServices() as $injectionToken => $service) {
                        $this->injector->register(is_string($injectionToken) ? $injectionToken : $service, $service);
                    }
                    foreach ($module->getEndpoints() as $endpoint) {
                        $this->routeRegister->registerEndpoint($endpoint);
                    }
                    $this->processModuleList($module->getModules());
                }
            }
            catch (Error $_) {
                $this->logger->exception(new InvalidClassException());
            }
        }
    }

}