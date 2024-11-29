<?php declare(strict_types = 1);

namespace Mys\Modules\Version;

use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase {
    /**
     * @return void
     */
    public function test_module_has_version_endpoint() {
        $module = new VersionModule();

        $endpoints = $module->getEndpoints();

        $this->assertCount(1, $endpoints);
        $this->assertEquals("/version", $endpoints[0]->getPath());
        $this->assertEquals("get", $endpoints[0]->getMethod());
        $this->assertEquals("text/plain", $endpoints[0]->getProduces());
    }

    /**
     * @return void
     */
    public function test_controller_returns_version_in_semver_format() {
        $controller = new VersionController();

        $version = $controller->getVersion();

        $this->assertMatchesRegularExpression("/^\d{1,3}.\d{1,3}.\d{1,3}$/", $version);
    }
}
