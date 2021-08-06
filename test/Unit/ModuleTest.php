<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\Test\Unit;

use CryptoScythe\BundledAssets\ConfigProvider;
use CryptoScythe\BundledAssets\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testModuleGetConfig()
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertArrayHasKey('view_helper_config', $config);
        $this->assertEquals(
            [
                ConfigProvider::class => [
                    ConfigProvider::CONFIG_KEY_CACHE_PATH => '',
                    ConfigProvider::CONFIG_KEY_MANIFESTS => [
                        ConfigProvider::CONFIG_KEY_DEFAULT_MANIFEST => '',
                    ]
                ]
            ],
            $config['view_helper_config']
        );
    }
}
