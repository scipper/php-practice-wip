<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Error;
use Mys\Core\Api\RouteRegister;
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
     * @param Logger $logger
     * @param array $moduleList
     * @param RouteRegister $routeRegister
     */
    public function __construct(Logger $logger, array $moduleList, RouteRegister $routeRegister) {
        $this->moduleList = $moduleList;
        $this->logger = $logger;
        $this->routeRegister = $routeRegister;
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