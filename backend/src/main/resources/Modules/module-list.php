<?php declare(strict_types = 1);

use Mys\Modules\RegisteredModules\RegisteredModulesModule;
use Mys\Modules\Todo\TodoModule;
use Mys\Modules\Version\VersionModule;

return [
    VersionModule::class,
    TodoModule::class,
    RegisteredModulesModule::class,
];
