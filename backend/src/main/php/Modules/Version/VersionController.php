<?php declare(strict_types = 1);

namespace Mys\Modules\Version;

class VersionController {

    /**
     * @return string
     */
    public function getVersion(): string {
        $composerJson = file_get_contents(__DIR__ . "/../../../../../composer.json");
        $json = json_decode($composerJson, true);
        return $json["version"];
    }

}