<?php declare(strict_types = 1);

namespace Mys\Core\Module;

class PHPFileModuleLoader implements ModuleLoader {

    /**
     * @var string
     */
    private string $filePath;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }

    /**
     * @return string|array
     */
    public function load(): string|array {
        return include $this->filePath;
    }
}