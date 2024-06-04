<?php declare(strict_types = 1);

namespace Mys\Core\Application;

use Error;
use Mys\Core\Api\RouteRegister;
use Mys\Core\Injection\Injector;
use Mys\Core\Logging\Logger;
use Mys\Core\Module\Module;

class Application
{
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
     * @param Logger $logger
     * @param RouteRegister $routeRegister
     */
    public function __construct(Injector $injector, array $moduleList, Logger $logger, RouteRegister $routeRegister)
    {
        $this->moduleList = $moduleList;
        $this->injector = $injector;
        $this->logger = $logger;
        $this->routeRegister = $routeRegister;
    }

    /**
     * @return void
     */
    public function init(): void
    {
        $this->processModuleList($this->moduleList);
    }

    /**
     * @param array $moduleList
     *
     * @return void
     */
    private function processModuleList(array $moduleList): void
    {
        foreach ($moduleList as $class)
        {
            try
            {
                $module = new $class();

                if (!($module instanceof Module))
                {
                    $this->logger->error(new ClassIsNotModuleException());
                }
                else
                {
                    foreach ($module->getClasses() as $injectionToken => $innerClass)
                    {
                        $this->injector->register(is_string($injectionToken) ? $injectionToken : $innerClass, $innerClass);
                    }
                    foreach ($module->getEndpoints() as $endpoint)
                    {
                        $this->routeRegister->registerEndpoint($endpoint);
                    }
                    $this->processModuleList($module->getModules());
                }
            }
            catch (Error $_)
            {
                $this->logger->error(new InvalidClassException());
            }
        }
    }

}