<?php declare(strict_types = 1);

namespace Mys\Core\Module;

class FileModuleLoader implements ModuleLoader
{
    /**
     * @var string
     */
    private string $filePath;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @inheritDoc
     */
    public function load(): string
    {
        return file_get_contents($this->filePath);
    }
}