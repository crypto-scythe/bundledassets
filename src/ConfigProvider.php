<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets;

use CryptoScythe\BundledAssets\View\Helper\BundledAssetsHelper;
use CryptoScythe\BundledAssets\View\Helper\BundledAssetsHelperFactory;

final class ConfigProvider
{
    public const CONFIG_KEY_CACHE_PATH = 'cachePath';
    public const CONFIG_KEY_MANIFESTS = 'manifests';
    public const CONFIG_KEY_DEFAULT_MANIFEST = 'default';

    public const GLOBAL_CONFIG_KEY_VIEW_HELPER_CONFIG = 'view_helper_config';

    public const CONFIG = [
        'view_helpers' => [
            'factories' => [
                BundledAssetsHelper::class => BundledAssetsHelperFactory::class,
            ],
            'aliases' => [
                'bundledAssets' => BundledAssetsHelper::class,
            ],
        ],
        self::GLOBAL_CONFIG_KEY_VIEW_HELPER_CONFIG => [
            self::class => [
                self::CONFIG_KEY_CACHE_PATH => '',
                self::CONFIG_KEY_MANIFESTS => [
                    self::CONFIG_KEY_DEFAULT_MANIFEST => '',
                ],
            ],
        ],
    ];

    public function __invoke(): array
    {
        return self::CONFIG;
    }
}
