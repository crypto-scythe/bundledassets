<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\Test\Helper;

use CryptoScythe\BundledAssets\ConfigProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\HeadScript;

final class TestContainerFactory
{
    public function __invoke(
        string $defaultManifestFile,
        string $testCacheFile = '',
        bool $enableCache = false,
        ?HeadLink $headLink = null,
        ?HeadScript $headScript = null,
        array $additionalManifests = []
    ): TestContainer {
        return new TestContainer(
            [
                'config' => [
                    ConfigAggregator::ENABLE_CACHE => $enableCache,
                    ConfigProvider::GLOBAL_CONFIG_KEY_VIEW_HELPER_CONFIG => [
                        ConfigProvider::class => [
                            ConfigProvider::CONFIG_KEY_CACHE_PATH => $testCacheFile,
                            ConfigProvider::CONFIG_KEY_MANIFESTS => array_merge(
                                [
                                    ConfigProvider::CONFIG_KEY_DEFAULT_MANIFEST => $defaultManifestFile,
                                ],
                                $additionalManifests,
                            )
                        ],
                    ],
                ],
            ],
            new TestContainer(
                [
                    HeadLink::class => $headLink ?? new HeadLink(),
                    HeadScript::class => $headScript ?? new HeadScript(),
                ],
            )
        );
    }
}
