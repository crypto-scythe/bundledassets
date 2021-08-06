<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\Test\Unit;

use CryptoScythe\BundledAssets\ConfigProvider;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    public function testConfigProviderDefaultConfiguration()
    {
        $configProvider = new ConfigProvider();
        $config = $configProvider->__invoke();

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
