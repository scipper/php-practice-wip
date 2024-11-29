<?php declare(strict_types = 1);

namespace Mys\Modules\Version;

class VersionController {

    /**
     * @return string
     */
    public function getVersion(): string {
        return "1.0.0";
    }

}