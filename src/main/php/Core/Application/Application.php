<?php

namespace Mys\Core\Application;

use Error;
use Mys\Core\Application\Logging\Logger;
use Mys\Core\Injection\Injector;
use Mys\Core\Module\Module;

class Application
{
    /**
     * @var string[] $moduleList
     */
    private array $moduleList;

    /**
     * @var Injector $injector
     */
    private Injector $injector;

    /**
     * @var Logger $logger
     */
    private Logger $logger;

    /**
     * @param Injector $injector
     * @param string[] $moduleList
     * @param Logger   $logger
     */
    public function __construct(Injector $injector, array $moduleList, Logger $logger)
    {
        $this->moduleList = $moduleList;
        $this->injector = $injector;
        $this->logger = $logger;
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
                    foreach ($module->getClasses() as $innerClass)
                    {
                        $this->injector->register($innerClass);
                    }
                    $this->processModuleList($module->getModules());
                }
            }
            catch (Error $e)
            {
                $this->logger->error(new InvalidClassException());
            }
        }
    }

}