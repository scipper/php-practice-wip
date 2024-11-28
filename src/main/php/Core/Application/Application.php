<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Error;
use Mys\Core\Api\RouteRegister;
use Mys\Core\ClassNotFoundException;
use Mys\Core\Injection\CyclicDependencyDetectedException;
use Mys\Core\Injection\Injector;
use Mys\Core\Logging\Logger;
use Mys\Core\Module\Module;

class Application {
    /**
     * @var string[]
     */
    private array $moduleList;

    /**
     * @var Injector
     */
    private Injector $injector;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var RouteRegister
     */
    private RouteRegister $routeRegister;

    /**
     * @param Injector $injector
     * @param string[] $moduleList
     * @param RouteRegister $routeRegister
     *
     * @throws ClassNotFoundException
     * @throws CyclicDependencyDetectedException
     */
    public function __construct(Injector $injector, array $moduleList, RouteRegister $routeRegister) {
        $this->moduleList = $moduleList;
        $this->injector = $injector;
        $this->logger = $this->injector->get(Logger::class);
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
                    foreach ($module->getClasses() as $injectionToken => $innerClass) {
                        $this->injector->register(is_string($injectionToken) ? $injectionToken : $innerClass, $innerClass);
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